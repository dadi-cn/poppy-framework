@extends('lemon.template.develop')
@section('develop-main')
	<div class="container">
		<div class="row" style="margin-top:20px">
			<div class="col-xs-12 col-sm-8 col-md-6 col-sm-offset-2 col-md-offset-3">
				{!! Form::open(['route'=> 'dev_home.login', 'id' => 'form_login' ]) !!}
				<fieldset>
					<h2>请登录：</h2>
					<hr class="colorgraph">
					<div class="form-group">
						{!! Form::text('username', null, ['class'=> 'form-control input-lg', 'placeholder' => '用户名']) !!}
					</div>
					<div class="form-group">
						{!! Form::password('password', ['class'=> 'form-control input-lg', 'placeholder' => '密码']) !!}
					</div>
					<div class="checkbox">
				    <label>
					    {!! Form::checkbox('remember_me', 1, true) !!} 记住我
				    </label>
				  </div>
					<hr class="colorgraph">
					<div class="row">
						<div class="col-xs-12 col-sm-12 col-md-12">
							{!! Form::button('登录', ['class' => 'btn btn-lg btn-success btn-block', 'type'=>'submit']) !!}
						</div>
					</div>
				</fieldset>
				{!! Form::close() !!}
			</div>
		</div>
	</div>
	<script>
	require(['jquery', 'lemon/util', 'jquery.validate', 'jquery.form'], function ($, util) {
		var conf = util.validate_conf({
			submitHandler : function (form) {
				$(form).ajaxSubmit({
					success : util.splash
				});
			},
			rules : {
				username : {
					required : true
				},
				password : {
					required : true
				}
			},
			messages : {
				username : {
					required : '请输入登录名'
				},
				password : {
					required : '请输入密码'
				}
			}
		}, 'bt3_self');
		$('#form_login').validate(conf);
	})
	</script>
@endsection