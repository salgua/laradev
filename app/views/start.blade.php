@extends('layouts.main')
@section('custom_css')
	<style>
		@import url(//fonts.googleapis.com/css?family=Lato:700);

		body {
			margin:0;
			font-family:'Lato', sans-serif;
			text-align:center;
			color: #999;
		}

		.welcome {
			width: 300px;
			height: 200px;
			position: absolute;
			left: 50%;
			top: 50%;
			margin-left: -150px;
			margin-top: -100px;
		}

		a, a:visited {
			text-decoration:none;
		}

		h1 {
			font-size: 32px;
			margin: 16px 0 0 0;
		}
	</style>
@stop

@section('header')
	@include('admin.partials.header')
@stop

@section('content')
	<div class="welcome">
		<a href="http://deved.it" title="Laravel PHP Framework"><img src="{{asset('img/logo_smart_platform.png');}}" alt="Deved Platform"></a>
		<h1>Welcome</h1>
	</div>
@stop