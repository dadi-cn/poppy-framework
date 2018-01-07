<?php namespace Poppy\Framework\Classes;

/*
 * 放置前端组件和扩展组件
 * @author     Mark <zhaody901@126.com>
 * @copyright  Copyright (c) 2013-2017 Sour Lemon Team
 */
use Collective\Html\FormBuilder as CollectiveFormBuilder;
use Poppy\Framework\Helper\StrHelper;
use Sour\Lemon\Logic\FileLogic;

class FormBuilder extends CollectiveFormBuilder
{


	/**
	 * 显示上传的单图
	 * @param       $url
	 * @param array $options
	 * @return string
	 */
	public function showThumb($url, $options = [])
	{
		$url       = $url ?: config('app.url') . 'assets/images/sour/nopic.gif';
		$options   = $this->html->attributes($options);
		$parse_str = '<img class="J_image_preview" src="' . $url . '" ' . $options . ' title="单击可打开图片, 按住 `ctrl` + `鼠标` 点击可以查看原图" >';
		return $parse_str;
	}


	/**
	 * 提示组件
	 * @param      $description
	 * @param null $name
	 * @return string
	 */
	public function tip($description, $name = null)
	{
		if ($name == null) {
			$icon = '<i class="fa fa-question-circle">&nbsp;</i>';
		}
		else {
			$icon = '<i class="fa ' . $name . '">&nbsp;</i>';
		}
		$trim_description = strip_tags($description);
		return <<<TIP
<a data-tip="{$description}" class="J_dialog" data-title="信息提示" title="{$trim_description}"
data-toggle="tooltip" data-placement="top">{$icon}</a>
TIP;
	}

	/**
	 * 生成树选择
	 * @param        $name
	 * @param        $tree
	 * @param string $selected
	 * @param array  $options
	 * @param string $id
	 * @param string $title
	 * @param string $pid
	 * @return string
	 */
	public function tree($name, $tree, $selected = '', $options = [], $id = 'id', $title = 'title', $pid = 'pid')
	{
		$Tree = new Tree();
		$Tree->init($tree, $id, $pid, $title);
		$treeArray = $Tree->getTreeArray(0, '');

		return $this->select($name, $treeArray, $selected, $options);
	}


	/**
	 * 生成排序链接
	 * @param        $name
	 * @param string $value
	 * @param string $route_name
	 * @return string
	 */
	public function order($name, $value = '', $route_name = '')
	{
		$input = \Input::all();
		$value = $value ?: (isset($input['_order']) ? $input['_order'] : '');
		switch ($value) {
			case $name . '_desc';
				$con  = $name . '_asc';
				$icon = '<i class="fa fa-sort-desc"></i>';
				break;
			case $name . '_asc':
				$con  = $name . '_desc';
				$icon = '<i class="fa fa-sort-asc"></i>';
				break;
			default:
				$icon = '<i class="fa fa-sort"></i>';
				$con  = $name . '_asc';
		}
		$input['_order'] = $con;
		if ($route_name) {
			$link = route($route_name, $input);
		}
		else {
			$link = '?' . http_build_query($input);
		}
		return '
			<a href="' . $link . '">' . $icon . '</a>
		';
	}


	/**
	 * 编辑器
	 * @param        $name
	 * @param string $value
	 * @param array  $options
	 * @return string
	 */
	public function kindeditor($name, $value = '', $options = [])
	{
		$options['id'] = $this->getIdAttribute($name, $options);
		$value         = (string) $this->getValueAttribute($name, $value);
		$append        = $this->html->attributes($options);
		$width         = isset($options['width']) ? $options['width'] : '100%';
		$height        = isset($options['height']) ? $options['height'] : '300px';
		$token         = upload_token();
		$returnUrl     = route('support.upload.return');
		$id            = $options['id'] ?: 'ke_' . StrHelper::random(4);
		$data          = <<<KindEditor
		<textarea name="$name" id="$id" $append>$value</textarea>
		<script>
		requirejs(['ke', 'global'], function (ke, lemon) {
			ke.create('#{$id}',{
				extraFileUploadParams:{
					'upload_token': '{$token}',
					'return_url': '{$returnUrl}'
				},
                uploadJson : lemon.upload_url + '?return_url={$returnUrl}',
                items:ke.iConfig.simple,
                width: '{$width}',
                height:'{$height}',
                minWidth:'300',
                resizeType: 1,
                filePostName:'image_file',
                allowFlashUpload:false,
                afterBlur : function(){
                    this.sync();
                },
                afterChange : function(){
                    this.sync();
                }
			});
		});
		</script>
KindEditor;
		return $data;
	}

	/**
	 * 颜色选取组件
	 * @param string $name
	 * @param string $value
	 * @param array  $options
	 * @return string
	 */
	public function spectrum($name, $value = '#FFF', $options = [])
	{
		$options['id'] = $this->getIdAttribute($name, $options);
		$value         = (string) $this->getValueAttribute($name, $value);
		$config        = [];
		$configStr     = '';
		if (isset($options['color'])) {
			$config['color'] = $options['color'];
		}
		if ($config) {
			$configStr = json_encode($config);
		}

		$html = <<<HTML
<input id="{$options['id']}" type="hidden" value="$value" name="$name">
<script>
requirejs(['jquery', 'jquery.spectrum'], function($){
	$(function(){
		$("#{$options['id']}").spectrum($configStr);
	})
})
</script>
HTML;
		return $html;
	}


	/**
	 * 生成日期选择器
	 * @param       $name
	 * @param       $value
	 * @param array $options
	 * @return string
	 */
	public function datepicker($name, $value = '', $options = [])
	{

		$options['id'] = $this->getIdAttribute($name, $options);
		$value         = (string) $this->getValueAttribute($name, $value);
		$class         = isset($options['class']) ? $options['class'] : '';
		$quickStr      = '';
		$quickScript   = '';
		if (isset($options['quick'])) {
			$quickNormal = [
				'三天' => '+3 days',
				'一周' => '+7 days',
				'半月' => '+15 days',
				'一月' => '+1 month',
				'半年' => '+6 month',
				'一年' => '+1 year',
			];
			$quickList   = [];
			foreach ($quickNormal as $date_key => $date_str) {
				$Date = new \DateTime();
				$Date->modify($date_str);
				$quickList[$Date->format('Y-m-d')] = $date_key;
			}
			$id          = 'datepicker_' . StrHelper::random(3) . '_quick';
			$quickStr    = self::select($id, $quickList, null, ['placeholder' => '长期', 'id' => $id, 'class' => 'form-control']);
			$quickScript = "
		var sel_datepicker = $('#" . $id . "');
			sel_datepicker.on('change', function () {
			datepicker.val(sel_datepicker.val());
		})";
		}

		$cfg_default = [
			'direction' => true,
		];
		$config      = array_merge($cfg_default, isset($options['config']) ? (array) $options['config'] : []);
		if ($config) {
			$configStr = json_encode($config);
		}
		else {
			$configStr = '';
		}

		$html = <<<HTML
		<input type="text" id="{$options['id']}" name="{$name}" value="{$value}" class="{$class}"/>
		{$quickStr}
		<script>
		    requirejs(['jquery', 'jquery.datepicker'], function ($) {
		        $(function () {
		        var datepicker = $('#{$options['id']}');
		            datepicker.Zebra_DatePicker({$configStr});
					{$quickScript}
		        })
		    });
		</script>
HTML;
		return $html;
	}


	/**
	 * 生成日期时间选择器
	 * @param        $name
	 * @param string $value
	 * @param array  $options
	 * @return string
	 */
	public function datetimepicker($name, $value = '', $options = [])
	{
		$options['id'] = $this->getIdAttribute($name, $options);
		$value         = (string) $this->getValueAttribute($name, $value);
		$class         = isset($options['class']) ? $options['class'] : '';
		$cfg_default   = [
			'lang'   => 'zh',
			'format' => 'Y-m-d H:i:s',
		];
		if (isset($options['timepicker']) && $options['timepicker'] == 'false') {
			$cfg_default['format'] = 'Y-m-d';
		}
		$config = array_merge($cfg_default, (array) (isset($options['config']) ? $options['config'] : []));
		if ($config) {
			$configStr = json_encode($config);
		}
		else {
			$configStr = '';
		}
		$html = <<<HTML
<input type="text" id="{$options['id']}" name="{$name}" value="{$value}" class="{$class}">
<script>
requirejs(['jquery', 'jquery.datetimepicker'], function($){
	$(function(){
		$("#{$options['id']}").datetimepicker({$configStr});
	});
});
</script>
HTML;
		return $html;
	}


	/**
	 * radio 选择器(支持后台)
	 * @param       $name
	 * @param array $lists
	 * @param null  $value
	 * @param array $options
	 * @return string
	 */
	public function radios($name, $lists = [], $value = null, $options = [])
	{
		$str   = '';
		$value = (string) $this->getValueAttribute($name, $value);
		if (isset($options['id'])) {
			$id = $options['id'];
		}
		else {
			$id = '';
		}
		$display_type = (isset($options['display_type']) && $options['display_type']) ? $options['display_type'] : 'inline';

		if ($display_type == 'bt3') {
			$str .= '<div class="btn-group" data-toggle="buttons">';
		}
		foreach ($lists as $key => $val) {
			if ($id) {
				$options['id'] = $id . '_' . $key;
			}
			switch ($display_type) {
				case 'inline':
					$str .= '<label class="radio-inline">';
					$str .= self::radio($name, $key, $value == $key, $options);
					$str .= $val;
					$str .= '</label>';
					break;
				case 'multi-line':
					$str .= '<div class="radio"><label>';
					$str .= self::radio($name, $key, $value == $key, $options);
					$str .= $val;
					$str .= '</label></div>';
					break;
				case 'bt3':
					$activeStr = $value == $key ? 'active' : '';
					$str       .= '<label class="btn btn-primary ' . $activeStr . '">';
					$str       .= self::radio($name, $key, $value == $key, $options);
					$str       .= $val;
					$str       .= '</label>';
					break;
			}
		}
		if ($display_type == 'bt3') {
			$str .= '</div>';
		}
		return $str;
	}


	/**
	 * 选择器
	 * @param       $name
	 * @param array $lists
	 * @param null  $value
	 * @param array $options
	 * @return string
	 */
	public function checkboxes($name, $lists = [], $value = null, $options = [])
	{
		$str       = '';
		$arrValues = [];
		$value     = (string) $this->getValueAttribute($name, $value);
		if (is_array($value)) {
			$arrValues = array_values($value);
		}
		elseif (is_string($value)) {
			if (strpos($value, ',') !== false) {
				$arrValues = explode(',', $value);
			}
			else {
				$arrValues = [$value];
			}
		}
		$inline = (isset($options['inline']) && $options['inline']) ? true : false;

		foreach ($lists as $key => $value) {
			if ($inline) {
				$str .= '<label class="checkbox-inline">';
				$str .= self::checkbox($name, $key, in_array($key, $arrValues), $options);
				$str .= $value;
				$str .= '</label>';
			}
			else {
				$str .= '<div class="checkbox"><label>';
				$str .= self::checkbox($name, $key, in_array($key, $arrValues), $options);
				$str .= $value;
				$str .= '</label></div>';
			}
		}
		return $str;
	}


	/**
	 * 标签输入
	 * @param string      $name
	 * @param null|string $value
	 * @param array       $options
	 * @return string
	 */
	public function bt3TagsInput($name, $value = null, $options = [])
	{
		$arrValues = [];
		$value     = (string) $this->getValueAttribute($name, $value);
		if (is_array($value)) {
			$arrValues = array_values($value);
		}
		elseif (is_string($value)) {
			if (strpos($value, ',') !== false) {
				$arrValues = explode(',', $value);
			}
			else {
				$arrValues = [$value];
			}
		}

		$classStr = isset($options['class']) ? $options['class'] : '';

		$value         = implode(',', $arrValues);
		$options['id'] = $this->getIdAttribute($name, $options);
		if (!$options['id']) {
			$options['id'] = 'bt3_tags_input_' . str_random(6);
		}
		$html = <<<HTML
<input id="{$options['id']}" type="hidden" value="{$value}" name="{$name}" class="{$classStr}">
<script>
requirejs(['jquery', 'jquery.bt3.tagsinput'], function($){
	$(function(){
		$("#{$options['id']}").tagsinput();
	})
})
</script>
HTML;
		return $html;
	}


	/**
	 * 多图上传
	 * todo
	 * @param       $name
	 * @param null  $values
	 *          [
	 *          [
	 *          'thumb'=> '',
	 *          'intro'=> '',
	 *          'is_cover'=> '',
	 *          ]
	 *          ]
	 * @param array $options
	 * @return string
	 */
	public function multiImage($name, $values = null, $options = [])
	{
		$options['id'] = $this->getIdAttribute($name, $options);
		$values        = !empty($values) ? $values : '';
		$doId          = $options['id'] . '_do';
		$uploadId      = $options['id'] . '_upload';

		$strImages = '';
		if ($values && is_array($values)) {
			foreach ($values as $key => $img) {
				$index     = 'old_' . $key;
				$strImages .= '
				<div class="imgbox" data-cover="' . $img['is_cover'] . '">
                    <div class="w_upload">
                        <a href="javascript:void(0)" class="item_close">删除</a>
                        <div class="item_box">
                        	<div class="photo">
                            <img data-big="' . SysPic::thumb($img['thumb']) . '" src="' . SysPic::thumb($img['thumb']) . '" class="js_picUP">
                            </div>
                            <div class="miaoshu-photo">
                                <input type="hidden" name="' . $name . '[' . $index . '][url]" value="' . $img['thumb'] . '" class="input-text">
                                <input type="text" name="' . $name . '[' . $index . '][alt]" value="' . $img['intro'] . '"  class="miaoshu-wenzi">
                            </div>
                        </div>
                      <div class="btn-set-fm js_set_fm"><span>点击设为封面</span></div>
                    </div>
                </div>';
			}
		}

		if ($strImages) {
			$display = 'display:block';
		}
		else {
			$display = 'display:none;';
		}
		$html = <<<HTML
<div>
	<div class="con-upload-photo" id="{$uploadId}" style="{$display}">
		<fieldset class="con-upload-photo-fie blue">
			<legend>上传图片列表</legend>
			<div class="J_image_ctr tupian-upload clearfix">
				<input name="{$name}_is_cover" id="js-fm-selected" type="hidden" value="1">
				{$strImages}
			</div>
		</fieldset>
	</div>
	<div class="con-upload-context clearfix">
		<a id="{$doId}" href="javascript:void(0);" class="upload-but fl">&nbsp;</a>
		<p class="fl">第一张默认为封面，每张最大5MB,支持jpg/gif/png格式</p>
	</div>
</div>
<script>
requirejs(['jquery', 'xundu/multi_image'], function ($, multi_image) {
	window.multi_image = multi_image.init("#{$doId}", '#{$uploadId}', "{$name}");
	window.multi_image.start();
})
</script>
HTML;
		return $html;
	}


	/**
	 * markdown 编辑框
	 * @param        $name
	 * @param string $value
	 * @param array  $options
	 * @return string
	 */
	public function editormd($name, $value = '', $options = [])
	{
		$options['style'] = 'display:none';
		$text             = parent::textarea($name, $value, $options);
		$options['id']    = 'lemon_md_' . StrHelper::random('5');
		$uploadUrl        = route_url('web:util.image', null, [
			'type' => 'original',
		]);
		$append           = <<<MARKDOWN
<div id="{$options['id']}">{$text}</div>
<script>
	var deps = [
		"jquery",
		"editormd",
		'editormd.link-dialog',
		'editormd.reference-link-dialog',
		'editormd.image-dialog',
		'editormd.code-block-dialog',
		'editormd.table-dialog',
		'editormd.emoji-dialog',
		'editormd.goto-line-dialog',
		'editormd.help-dialog',
		'editormd.html-entities-dialog',
		'editormd.preformatted-text-dialog'
	];
	requirejs(deps, function ($, editormd) {
		editormd("{$options['id']}", {
			width : "100%",
			height : 640,
			path : '/assets/js/libs/codemirror/5.26.0/',
			pluginPath : '/assets/js/libs/editormd/1.5.0/plugins/',
			codeFold : true,
			searchReplace : true,
			saveHTMLToTextarea : false,                   // 保存HTML到Textarea
			htmlDecode : "style,script,iframe|on*",       // 开启HTML标签解析，为了安全性，默认不开启
			emoji : true,
			taskList : true,
			lineNumbers: false,
			tocm : true,         // Using [TOCM]
			autoLoadModules : false,
			previewCodeHighlight : true,
			flowChart : false,
			sequenceDiagram : false,
			imageUpload : false,
			imageFormats : ["jpg", "jpeg", "gif", "png"],
			imageUploadURL : '{$uploadUrl}'
		});

	})
</script>
MARKDOWN;
		return $append;
	}

	protected function replace($item)
	{
		return str_replace(['[', ']'], '_', $item);
	}
}