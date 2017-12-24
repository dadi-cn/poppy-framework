@if (isset($_sub_menu))
	<nav class="navbar navbar-default">
		<div class="navbar-header">
			<a class="navbar-brand" href="{!! route('dev_home.cp') !!}"><i class="fa fa-home"></i></a>
		</div>
		<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
			<ul class="nav navbar-nav">
				@if (isset($_sub_menu['sub_group']) && $_sub_menu['sub_group'])
					<li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button">{!! $_sub_menu['title'] !!} <span class="caret"></span></a>
						<ul class="dropdown-menu">
							@foreach($_sub_menu['sub_group'] as $link)
								<li><a href="{!! route_url($link['route'], null, $link['param']) !!}">{!! $link['title'] !!}</a></li>
							@endforeach
						</ul>
					</li>
				@endif
				@if (isset($_sub_menu['direct']) && $_sub_menu['direct'])
					@foreach($_sub_menu['direct'] as $link)
						<li><a href="{!! route_url($link['route'], null, $link['param']) !!}">{!! $link['title'] !!}</a></li>
					@endforeach
				@endif
			</ul>
			@if (isset($self_menu))
				<ul class="nav navbar-nav navbar-right">
					<li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">帮助文档 <span class="caret"></span></a>
						<ul class="dropdown-menu">
							@foreach($self_menu as $title => $link)
								<li><a target="_blank" href="{!! $link !!}">{!! $title !!}</a></li>
							@endforeach
						</ul>
					</li>
				</ul>
			@endif
		</div>
	</nav>
@endif