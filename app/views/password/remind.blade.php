@extends('admin.layouts.main')
@section('content')
	<div ng-app="app">
    		<div class="form-box" id="login-box" ng-controller="MainCtrl as main">
                <div class="header">{{{trans('Password recovery')}}}</div>
                {{ Form::open(array('url' => 'password/remind', 'novalidate' => '', 'name' => 'remindform')) }}
                    <div class="body bg-gray">
                        <div class="form-group has-feedback" ng-class="{ 'has-error': remindform.email.$invalid && remindform.email.$dirty }">
    						{{Form::email('email', '', array('class' => 'form-control', 'placeholder' => 'Email', 'required' => '', 'ng-model' => 'email'))}}
                            <span ng-show="remindform.email.$invalid && remindform.email.$dirty" class="help-block">{{trans('Insert a valid email address')}}</span>
                        </div>         
                    </div>
                    <div class="footer">   
                    	{{Form::submit(trans('Submit'), array('class' => 'btn bg-olive btn-block', 'ng-disabled' => 'remindform.$invalid'))}}                                                                              
                    </div>
                {{ Form::close() }}
            </div>
        </div>
@stop
@section('angular')
<script>
    var app = angular.module('app', []);
    app.controller('MainCtrl', ['$scope', function($scope) {

    }]);
</script>
@stop