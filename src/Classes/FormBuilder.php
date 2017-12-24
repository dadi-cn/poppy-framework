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
	 * 上传缩略图
	 * @param       $name
	 * @param null  $value
	 * @param array $options
	 * @return string
	 */
	public function webUploader($name, $value = null, $options = [])
	{
		if (!isset($options['name'])) $options['name'] = $name;

		$options['id']          = $this->getIdAttribute($name, $options);
		$value                  = (string) $this->getValueAttribute($name, $value);
		$options['interactive'] = isset($options['interactive']) && $options['interactive'] ? true : false;

		$id = StrHelper::random(4);

		$thumb_key   = $value ?: '';
		$display_str = !$value ? "class=\"hidden icons\"" : "class=\"icons\"";
		$extension   = isset($options['ext']) ? $options['ext'] : 'zip';
		$desc        = FileLogic::type($extension, 'desc');
		$extensions  = FileLogic::type($extension, 'ext_string');
		$fileSize    = 20 * 1024 . 'Kb';
		if ($extension == 'image') {
			$iconStr = '';
			if ($value) {
				$iconStr = '<img src="' . $value . '">';
			}
			$fileSize = '500Kb';
		}
		else {
			$iconStr = '<i id="web_uploader_preview_' . $id . '" class="fa fa-file-archive-o fa-lg"></i>';
		}

		$parseStr = <<<CONTENT
		<div id="uploader_{$id}" class="sl-webuploader clearfix sl-webuploader_{$extension}">
		    <input type="hidden" name="{$name}" value="{$thumb_key}" id="web_uploader_url_{$id}"/>
		    <div id="thelist_{$id}" class="uploader-list"></div>
		    <div class="clearfix">
			     <div class="btns">
			          <div id="picker_{$id}">{$desc}</div>
			     </div>
			     <span id="web_uploader_preview_{$id}_ctr" {$display_str} class="">
					  {$iconStr}
					  <i id='web_uploader_img_del_{$id}' class="fa fa-times fa-lg"></i>
				 </span>
		    </div>
		    <div class="statusBar">
		        <div class="progress">
		            <span><span>
		        </div>
		        <div class="info"></div>
		    </div>
		</div>
		<script>
			require(['jquery','jquery.webuploader', 'global'], function($, WebUploader, lemon){
				var extension = '{$extension}';
				var uploader = WebUploader.create({
					dnd : '.sl-webuploader',
					swf : lemon.url_js + '/libs/jquery.webuploader/0.1.5/Uploader.swf',  // swf文件路径
					server: lemon.upload_file,  // 文件接收服务端
					auto  : true,
					accept : {
					    title: '{$desc}',
					    extensions: '{$extensions}'
					},
					formData : {
					    'ext' : '{$extension}'
					},
					fileNumLimit: 1,
					fileSingleSizeLimit: '{$fileSize}',
					// fileSizeLimit: '{$fileSize}',
					fileVal: 'uploader_file',
					pick: '#picker_{$id}'   // 选择文件的按钮。可选
					
				});
				var \$wrap = $('#uploader_{$id}');
				var \$queue = $('<ul class="filelist"></ul>')
                    .appendTo(\$wrap.find('.queueList'));
                var  percentages = {};
                var \$statusBar = \$wrap.find('.statusBar');
                var \$progress = \$statusBar.find('.progress').hide();
                var state = 'pedding';
                var fileCount = 0;
                var fileSize = 0;
                var \$info = \$statusBar.find('.info').hide();
				uploader.onFileQueued = function (file) {
					addFile(file);
				};
				uploader.onUploadProgress = function (file, percentage) {
					var \$li = $('#' + file.id),
					    \$percent = \$li.find('.progress span');
					
					\$percent.css('width', percentage * 100 + '%');
					percentages[file.id][1] = percentage;
					updateTotalProgress();
				};
				uploader.onUploadSuccess = function(file, obj_resp){
					if (obj_resp.status == 'error') {
					    alert(obj_resp.msg);
					} else {
						if (extension == 'image') {
							var \$img = $('<img >');
							\$img.prop('src', obj_resp.url);
							\$img.prop('class', 'J_image_preview');
							$("#web_uploader_preview_{$id}_ctr").append(\$img).removeClass('hidden').show();
							$("#web_uploader_preview_{$id}").css('display', 'none');
							$('#web_uploader_url_{$id}').val(obj_resp.url);
						} else {
							$('#web_uploader_url_{$id}').val(obj_resp.destination);
						    $('#web_uploader_preview_{$id}_ctr').removeClass('hidden');
						}
						
						
					}
					$("#web_uploader_preview_{$id}_ctr").show();
				}
				
				        // 当有文件添加进来时执行，负责view的创建
		        function addFile(file) {
		            var \$li = $('<li id="' + file.id + '">' +
		                    '<p class="title">' + file.name + '</p>' +
		                    '<p class="progress"><span></span></p>' +
		                    '</li>'),
		
		                \$prgress = \$li.find('p.progress span'),
		                \$info = $('<p class="error"></p>'),
		
		                showError = function (code) {
		                    switch (code) {
		                        case 'exceed_size':
		                            text = '文件大小超出';
		                            break;
		
		                        case 'interrupt':
		                            text = '上传暂停';
		                            break;
		
		                        default:
		                            text = '上传失败，请重试';
		                            break;
		                    }
		
		                    \$info.text(text).appendTo(\$li);
		                };
		
		            if (file.getStatus() === 'invalid') {
		                showError(file.statusText);
		            } else {
		                // @todo lazyload
		                percentages[file.id] = [file.size, 0];
		                file.rotation = 0;
		            }
		
		            file.on('statuschange', function (cur, prev) {
		                if (prev === 'progress') {
		                    \$prgress.hide().width(0);
		                } else if (prev === 'queued') {
		                    \$li.off('mouseenter mouseleave');
		                }
		
		                // 成功
		                if (cur === 'error' || cur === 'invalid') {
		                    console.log(file.statusText);
		                    showError(file.statusText);
		                    percentages[file.id][1] = 1;
		                } else if (cur === 'interrupt') {
		                    showError('interrupt');
		                } else if (cur === 'queued') {
		                    percentages[file.id][1] = 0;
		                } else if (cur === 'progress') {
		                    \$info.remove();
		                    \$prgress.css('display', 'block');
		                } else if (cur === 'complete') {
		                    \$li.append('<span class="success"></span>');
		                }
		
		                \$li.removeClass('state-' + prev).addClass('state-' + cur);
		            });
		
		            \$li.appendTo(\$queue);
		        }
		        function updateTotalProgress() {
		            if( state == 'uploading') {
		                \$progress.show();
		            }
		            var loaded = 0,
		                total = 0,
		                spans = \$progress.children(),
		                percent;
		
		            $.each(percentages, function (k, v) {
		                total += v[0];
		                loaded += v[0] * v[1];
		            });
		            percent = total ? loaded / total : 0;
		
		            spans.eq(0).text(Math.round(percent * 100) + '%');
		            spans.eq(0).css('width', Math.round(percent * 100) + '%');
		            updateStatus();
		        }
		        
		        function updateStatus() {
		            var text = '', stats;
		
		            if (state === 'ready') {
		                // 
		            } else if (state === 'confirm') {
		                //
		
		            }else if (state === 'pedding') {
		                //
		
		            } else {
		                stats = uploader.getStats();
		                text = '共' + fileCount + '个（' +
		                    WebUploader.formatSize(fileSize) +
		                    '），已上传' + stats.successNum + '个';
		
		                if (stats.uploadFailNum) {
		                    text += '，失败' + stats.uploadFailNum + '个';
		                }
		                \$info.html(text).show();
		            }
		            
		        }
				function setState(val) {
		            var file, stats;
		
		            if (val === state) {
		                return;
		            }
		
		            state = val;
		
		            switch (state) {
		                case 'pedding':
		                    \$queue.parent().removeClass('filled');
		                    \$queue.hide();
		                    \$statusBar.addClass('element-invisible');
		                    uploader.refresh();
		                    break;
		
		                case 'ready':
		                    $('#filePicker2').removeClass('element-invisible');
		                    \$queue.parent().addClass('filled');
		                    \$queue.show();
		                    \$statusBar.removeClass('element-invisible');
		                    uploader.refresh();
		                    break;
		
		                case 'uploading':
		                    $('#filePicker2').addClass('element-invisible');
		                    \$progress.show();
		                    break;
		
		                case 'paused':
		                    \$progress.show();
		                    break;
		
		                case 'confirm':
		                    \$progress.hide();
		
		                    stats = uploader.getStats();
		                    if (stats.successNum && !stats.uploadFailNum) {
		                        setState('finish');
		                        return;
		                    }
		                    break;
		                case 'finish':
		                    stats = uploader.getStats();
		                    if (stats.successNum) {
		                        // alert('上传成功');
		                    } else {
		                        // 没有成功的图片，重设
		                        state = 'done';
		                        location.reload();
		                    }
		                    break;
		            }
		
		            updateStatus();
		        }
		
		        uploader.onUploadProgress = function (file, percentage) {
		            var \$li = $('#' + file.id),
		                \$percent = \$li.find('.progress span');
		
		            \$percent.css('width', percentage * 100 + '%');
		            percentages[file.id][1] = percentage;
		            updateTotalProgress();
		        };
		
		        uploader.onFileQueued = function (file) {
		            fileCount++;
		            fileSize += file.size;
		
		            if (fileCount === 1) {
		                \$statusBar.show();
		            }
		
		            addFile(file);
		            setState('ready');
		            updateTotalProgress();
		        };
		
		        uploader.onFileDequeued = function (file) {
		            fileCount--;
		            fileSize -= file.size;
		
		            if (!fileCount) {
		                setState('pedding');
		            }
		
		            removeFile(file);
		            updateTotalProgress();
		
		        };
		
		        uploader.on('all', function (type) {
		            var stats;
		            switch (type) {
		                case 'uploadFinished':
		                    setState('confirm');
		                    break;
		
		                case 'startUpload':
		                    setState('uploading');
		                    break;
		
		                case 'stopUpload':
		                    setState('paused');
		                    break;
		
		            }
		        });
		
		        uploader.onError = function (code) {
		            if (code == 'Q_EXCEED_SIZE_LIMIT') {
		                alert('文件大小超出限制' + '{$fileSize}')
		            } else {
		                alert('Error: ' + code);
		            }
		           
		        };
		
		
		        \$info.on('click', '.retry', function () {
		            uploader.retry();
		        });
		
		        \$info.on('click', '.ignore', function () {
		            alert('todo');
		        });
		
		        updateTotalProgress();
				
				$("#web_uploader_img_del_{$id}").click(function () {
					$("#web_uploader_preview_{$id}_ctr").hide();
					$("input[name={$name}]").val('');
					uploader.reset();
					\$info.hide();
				});
			})
		</script>
CONTENT;
		return $parseStr;
	}

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