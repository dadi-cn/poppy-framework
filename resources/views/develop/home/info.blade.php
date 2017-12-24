@extends('lemon.template.bt3')
@section('bootstrap-main')
	@include('develop.inc.sub_menu_bt3')
	<div class="layout1000">
		<div class="row">
			<div class="col-md-12">
				{!! phpinfo() !!}
			</div>
		</div>
	</div>
@endsection