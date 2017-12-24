@extends('lemon.template.bt3-doc')
<div class="container">
	@include('develop.inc.sub_menu_bt3')
</div>
<header class="navbar navbar-static-top bs-docs-nav" id="top" role="banner">
	<div class="container">
		<nav id="bs-navbar" class="collapse navbar-collapse">
			<ul class="nav navbar-nav">
				@foreach($dirs as $dir)
				<li @if ($dir == $current_dir)  class="active" @endif>
					<a href="{!! route('dev_doc.index', ['dir'=> $dir]) !!}" title="{!! $dir !!}">{!! $dir !!}</a>
				</li>
				@endforeach
			</ul>
		</nav>
	</div>
</header>
@section('bt3-doc-main')
<!--日志记录-->
@if (isset($output))
<div class="logRecord col-sm-2" id="logRecord">
	<ul class="list-group">
		@foreach($output as $o)
		<li class="list-group-item">
			<span class="time badge"> {!! $o[1] !!} </span><a class="tooltip-show" data-toggle="tooltip" data-placement="bottom" target="_self" href="#" title="{!! $o[4] !!}">{!! $o[4] !!}</a>
			<br>
			<p>{!! $o[2] !!}</p>
		</li>
		@endforeach
	</ul>
</div>
@endif
<!--内容区-->
<div class="container bs-docs-container mt10">
	<div class="row" id="top">
		<div class="col-md-3">
			<nav class="bs-docs-sidebar hidden-print hidden-xs hidden-sm affix" id="affix">
				<div class="panel-group" id="accordion" role="tablist">
					@foreach($detail['files'] as $k => $file_sub)
					<div class="panel panel-default">
						<div class="panel-heading" role="tab">
							<h4 class="panel-title">
								<a class="collapsed" role="button" title="{!!  $file_sub['folder'] !!}" data-toggle="collapse" data-parent="#accordion" href="#collapse-{!! $k !!}" aria-controls="collapse-{!! $k !!}">
									{!!  $file_sub['folder']!!}
								</a>
							</h4>
						</div>
						<div id="collapse-{!!  $k !!}" class="panel-collapse @if (!in_array($detail['fb'], $file_sub['files'])) collapse @endif" role="tabpanel">
							<div class="panel-body">
								<ul class="nav">
									@foreach($file_sub['files'] as $f)
									<li class="@if ( $detail['fb'] == $f)active @endif" data-title="{!!  $f !!}">
										<a href="{!! route('dev_doc.index', ['file'=> $f, 'dir'=> $current_dir]) !!}" title="{!! $f !!}">{!!  substr($f, strlen($file_sub['folder'])) !!}</a>
									</li>
									@endforeach
								</ul>
							</div>
						</div>
					</div>
					@endforeach
				</div>
			</nav>
		</div>
		<div class="col-md-9">
			<div id="markdown" class="bs-docs-section">
				{!! $html !!}
			</div>
		</div>
	</div>
</div >

<!--标题导航-->
<div class="titleNav col-md-2" id="titleNav">
	<nav class="bs-docs-sidebar">
		<ul class="nav bs-docs-sidenav">
			@foreach($titles as $h2 => $h3s)
			<li class="">
				<a href="#{!! md5($h2) !!}" title="{!! $h2 !!}">{!! $h2 !!}</a>
				<ul class="nav">
					@foreach($h3s as $h3)
					<li><a href="#{!! md5($h3) !!}" title="{!! $h3 !!}">{!! $h3 !!}</a></li>
					@endforeach
				</ul>
			</li>
			@endforeach
		</ul>
		<a class="back-to-top" href="#top" title="返回顶部">
			返回顶部
		</a>
	</nav>
</div>

<style>
	body {
		font-family: "Helvetica Neue", Helvetica, Microsoft Yahei, Hiragino Sans GB, WenQuanYi Micro Hei, sans-serif;
		position: relative;
	}
	.bs-docs-section > p, .bs-docs-section > ul,
	.bs-docs-section > ol, .bs-callout > p,
	.bs-callout > ol, .bs-callout > ul {
		font-size: 16px;
		line-height: 1.75;
		margin-bottom: 1em;
	}
	.bs-callout *:last-child {
		margin-bottom: 0;
	}
	.logRecord .affix{ top:20px;}
	.titleNav{bottom:50px; position: fixed;}
	.titleNav .bs-docs-sidebar.affix{ top:auto; bottom: 100px;}
</style>
<script>
requirejs(['jquery', 'bt3'], function ($) {
	$(function () {
		$('.tooltip-show').tooltip(); //提示信息
		//初始化 日志记录与标题导航的位置
		var topOffset=0;
		var windowWidth=$(window).width();
		var containerWidth=$(".container").width();
		var $log=$("#logRecord");
		var $titleNav=$("#titleNav");
		var logW=$log.width();
		var logLeft=(windowWidth-containerWidth-40)/2-logW;
		var titleNavRight=(windowWidth+containerWidth)/2;
		$log.css({'left':logLeft,'top':topOffset+"px"});
		$titleNav.css({'left':titleNavRight,'bottom':"50px"});
		$log.find("ul").css("width",logW).affix({offset:80 });
		$titleNav.find("nav").affix({offset:80 });
		$('#affix').affix({offset : 15});
		$('body').scrollspy({target : '#titleNav'})
	})
})
</script>
@stop