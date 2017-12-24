@extends('lemon.template.bt3')
@section('bootstrap-main')
	@include('develop.inc.sub_menu_bt3')
	<div class="layout1000">
		<div class="row">
			<div class="col-md-12">
				<table class="table">
					<tr>
						<td class="w120">说明</td>
						<td>是否</td>
						<td>值</td>
						<td>备注</td>
					</tr>
					<tr>
						<td class="w120">网站缓存</td>
						<td>@if (Cache::has('site')) <i class="fa fa-check text-success"></i> @else <i class="fa fa-close text-danger"></i> @endif</td>
						<td></td>
						<td>后台登录对缓存进行重新编辑</td>
					</tr>
					<tr>
						<td class="w120">角色缓存</td>
						<td>@if (Cache::has('role')) <i class="fa fa-check text-success"></i> @else <i class="fa fa-close text-danger"></i> @endif</td>
						<td></td>
						<td>后台登录对角色进行重新编辑</td>
					</tr>
					<tr>
						<td class="w120">global.js</td>
						<td>@if (file_exists(public_path('js/global.js'))) <i class="fa fa-check text-success"></i> @else <i class="fa fa-close text-danger"></i> @endif</td>
						<td></td>
						<td>是否存在 JS 全局文件</td>
					</tr>
					<tr>
						<td class="w120">php-bcmath</td>
						<td>@if (function_exists('bcadd')) <i class="fa fa-check text-success"></i> @else <i class="fa fa-close text-danger"></i> @endif</td>
						<td></td>
						<td>是否存在 bcmath 库</td>
					</tr>
					<tr>
						<td class="w120">php-gd</td>
						<td>@if (function_exists('imagealphablending')) <i class="fa fa-check text-success"></i> @else <i class="fa fa-close text-danger"></i> @endif</td>
						<td></td>
						<td>是否存在 GD 库</td>
					</tr>
					<tr>
						<td class="w120">订单自动结算时间</td>
						<td>@if (site('order_over_hour')) <i class="fa fa-check text-success"></i>  @else <i class="fa fa-close text-danger"></i> @endif</td>
						<td>{!! site('order_over_hour') !!}</td>
						<td>订单自动结算时间</td>
					</tr>
				</table>
			</div>
		</div>
	</div>
@endsection