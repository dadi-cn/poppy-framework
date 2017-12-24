@extends('lemon.template.bt3')
@section('bootstrap-main')
	<nav class="navbar navbar-default">
		<div class="navbar-header">
			<a class="navbar-brand" href="#">
				样式/功能入口
			</a>
		</div>
		<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
			<ul class="nav navbar-nav">
				@foreach($menus as $key => $menu)
				<li><a href="#{!! $key !!}">{!! $menu['title'] !!} </a></li>
				@endforeach
			</ul>
		</div>
	</nav>
	@foreach($menus as $nav_key => $nav)
		<div class="row">
			<div class="col-md-12">
				<h3 id="{{$nav_key}}">{!! $nav['title'] !!}</h3>
				<table class="table table-bordered">
					@foreach($nav['group'] as $nav_group)
					<?php
					if (isset($nav['menu_link'][$nav_group])) {
						$group_link = $nav['menu_link'][$nav_group];
					} else {
						continue;
					}
					?>
					@if (isset($group_link['sub_group']) && !empty($group_link['sub_group'])) <!-- has children-->
					<tr>
						<td class="col-lg-1">{{$group_link['title']}}</td>
						<td>
							@foreach($group_link['sub_group'] as $sub)
								<a class="btn btn-default" href="{{ route_url($sub['route'], null, $sub['param'])}}" data-rel="{{$nav_key}}">{{$sub['title']}}</a>
							@endforeach
						</td>
					</tr>
					@endif
					@if (isset($group_link['direct']) && !empty($group_link['direct']))
						<tr>
							<td class="col-lg-1">
								<button class="btn btn-success">{!! $group_link['title'] !!}</button>

							</td>
							<td>
								@foreach($group_link['direct'] as $sub)
									<a class="btn btn-default" href="{{ route_url($sub['route'], null, $sub['param'])}}" data-rel="{{$nav_key}}">{{$sub['title']}}</a>
								@endforeach
							</td>
						</tr>
					@endif
					@endforeach
				</table>
			</div>
		</div>
	@endforeach
@endsection