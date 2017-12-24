<?php namespace Poppy\Framework\Classes\Logic;

use App\Lemon\Repositories\Application\Traits\AppTrait;
use Carbon\Carbon;
use Intervention\Image\Constraint;
use OSS\Core\OssException;
use OSS\OssClient;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * 图片上传
 * @package App\Lemon\Dailian\Action
 */
class UploadLogic
{

	use AppTrait;

	protected $destination = '';

	protected $disk = 'public_uploads';

	private $allowedExtensions = ['zip'];

	protected static $extensions = [
		'image' => [
			'extension'   => 'jpg,jpeg,png,gif',
			'description' => '请选择图片',
		],
		'zip'   => [
			'extension'   => 'zip',
			'description' => '选择压缩包',
		],
		'rp'    => [
			'extension'   => 'rp',
			'description' => '请选择 Rp 文件',
		],
		'rplib' => [
			'extension'   => 'rplib',
			'description' => '请选择原型组件',
		],
		'xls'   => [
			'extension'   => 'xls,xlsx',
			'description' => '请选择xls文件',
		],
	];

	private $saveAli = false;

	/** @var int 默认使用水印库 */
	private $wm = 1;

	public function __construct($wm = 1)
	{
		$this->wm = $wm;
	}

	public function setExtension($extension = [])
	{
		$this->allowedExtensions = $extension;
	}

	/**
	 * 获取本地磁盘存储
	 * @return \Illuminate\Filesystem\FilesystemAdapter
	 */
	public function storage()
	{
		return \Storage::disk($this->disk);
	}

	/**
	 * 使用文件/form 表单形式上传获取并且保存
	 * @param UploadedFile $file
	 * @return mixed
	 */
	public function saveFile($file)
	{
		if (!$file) {
			return $this->setError('没有上传任何文件');
		}

		if ($file->isValid()) {
			// 存储
			if ($file->getClientOriginalExtension() && !in_array(strtolower($file->getClientOriginalExtension()), $this->allowedExtensions)) {
				return $this->setError('你只允许上传 "' . implode(',', $this->allowedExtensions) . '" 格式');
			}

			// 磁盘对象
			$Disk      = $this->storage();
			$extension = $file->getClientOriginalExtension();
			if (!$extension) {
				$extension = 'png';
			}
			$fileRelativePath = $this->genRelativePath($extension);
			$zipContent       = file_get_contents($file);
			$Disk->put($fileRelativePath, $zipContent);

			// 缩放图片
			if (in_array($extension, self::type('image', 'ext_array')) && $extension != 'gif') {
				try {
					$Image = \Image::make(disk_path($this->disk) . $fileRelativePath);
					$Image->resize(1920, null, function (Constraint $constraint) {
						$constraint->aspectRatio();
						$constraint->upsize();
					});
					$Image->save(null, 65);
				} catch (\Exception $e) {
					return $this->setError($e->getMessage());
				}
			}

			$this->destination = $fileRelativePath;
			return true;
		}
		else {

			return $this->setError($file->getErrorMessage());
		}
	}

	/**
	 * 裁剪和压缩
	 * @param int  $width
	 * @param int  $height
	 * @param bool $crop 是否进行裁剪
	 * @param int  $quality
	 * @return bool
	 */
	public function resize($width = 1920, $height = 1440, $crop = false, $quality = 60)
	{
		$Image = \Image::make(disk_path($this->disk) . $this->destination);

		if ($crop) {
			$widthCalc  = $Image->width() > $width ? $width : $Image->width();
			$heightCalc = $Image->height() > $height ? $height : $Image->height();
			$widthMax   = $Image->width() < $width ? $width : $Image->width();
			$heightMax  = $Image->height() < $height ? $height : $Image->height();

			// calc x, calc y
			$x = 0;
			$y = 0;
			if ($widthCalc >= $width) {
				$x = ceil(($widthMax - $widthCalc) / 2);
			}
			if ($heightCalc >= $height) {
				$y = ceil(($heightMax - $heightCalc) / 2);
			}
			if ($x || $y) {
				$Image->crop($width, $height, $x, $y);
			}

		}


		$Image->resize($width, $height, function (Constraint $constraint) {
			$constraint->aspectRatio();
			$constraint->upsize();
		});
		$Image->save(null, $quality);
		return true;
	}

	/**
	 * 保存内容或者流方式上传
	 * @param $content
	 * @return bool
	 */
	public function saveInput($content)
	{
		// 磁盘对象
		$Disk = $this->storage();

		$fileRelativePath = $this->genRelativePath();
		$Disk->put($fileRelativePath, $content);

		// 缩放图片
		$Image = \Image::make(disk_path($this->disk) . $fileRelativePath);
		$Image->resize(1920, null, function (Constraint $constraint) {
			$constraint->aspectRatio();
			$constraint->upsize();
		});
		$Image->save(null, 60);

		$this->destination = $fileRelativePath;
		return true;
	}

	/**
	 * 类型
	 * @param        $type
	 * @param string $return_type
	 * @return array
	 */
	public static function type($type, $return_type = 'ext_string')
	{
		if (!isset(self::$extensions[$type])) {
			$ext = self::$extensions['image'];
		}
		else {
			$ext = self::$extensions[$type];
		}
		switch ($return_type) {
			case 'desc':
				return $ext['description'];
				break;
			case 'ext_array':
				return explode(',', $ext['extension']);
				break;
			case 'ext_string':
			default:
				return $ext['extension'];
				break;
		}
	}

	/**
	 * 保存到阿里云
	 * @param bool $delete_local 删除本地
	 * @return bool
	 */
	public function saveAli($delete_local = true)
	{
		if ($this->wm) {
			$endpoint = config('oss.endpoint');
			$bucket   = config('oss.bucket_name');
		}
		else {
			$endpoint = config('oss.nwm_endpoint');
			$bucket   = config('oss.nwm_bucket_name');
		}
		$accessKeyId     = config('oss.app_key');
		$accessKeySecret = config('oss.app_secret');
		$this->saveAli   = true;
		try {
			$ossClient = new OssClient($accessKeyId, $accessKeySecret, $endpoint, false);
			$ossClient->putObject($bucket, $this->destination, $this->storage()->get($this->destination));
			if ($delete_local) {
				$this->storage()->delete($this->destination);
			}
			return true;
		} catch (OssException $e) {
			return $this->setError($e->getMessage());
		}
	}

	public function setDestination($destination)
	{
		$this->destination = $destination;
	}


	public function getDestination()
	{
		return $this->destination;
	}


	/**
	 * 图片url的地址
	 * @return string
	 */
	public function getUrl()
	{
		if ($this->saveAli) {
			if ($this->wm) {
				$url = config('oss.return_url') . '/' . $this->destination;
			}
			else {
				$url = config('oss.nwm_return_url') . '/' . $this->destination;
			}
			return oss_image($url);
		}
		else {
			// 磁盘 public_uploads 对应的是根目录下的 uploads, 所以这里的目录是指定的
			return config('app.url') . '/uploads/' . $this->destination;
		}
	}


	private function genRelativePath($extension = 'png')
	{
		$now      = Carbon::now();
		$fileName = $now->format('is') . str_random(8) . '.' . $extension;
		return $now->format('Ym/d/H/') . $fileName;
	}
}