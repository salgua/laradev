@extends('layouts.main')
@section('content')
        <div ng-app="app">
    		<div class="form-box" id="login-box" ng-controller="MainCtrl as main">
                <div class="header">{{{trans('Sign In')}}}</div>
                {{ Form::open(array('url' => 'login', 'novalidate' => '', 'name' => 'loginform')) }}
                    <div class="body bg-gray">
                        <div class="form-group has-feedback" ng-class="{ 'has-error': loginform.email.$invalid && loginform.email.$dirty }">
    						{{Form::email('email', '', array('class' => 'form-control', 'placeholder' => 'Email', 'required' => '', 'ng-model' => 'email'))}}
                            <span ng-show="loginform.email.$invalid && loginform.email.$dirty" class="help-block">{{trans('Insert a valid email address')}}</span>
                        </div>
                        <div class="form-group">
                        	{{Form::password('password', array('class' => 'form-control', 'placeholder' => 'Password', 'required' => '', 'ng-model' => 'password'))}}
                        </div>          
                        <div class="form-group">
                        	{{Form::checkbox('remember_me')." ".trans('Remember me')}}
                        </div>
                    </div>
                    <div class="footer">   
                    	{{Form::submit(trans('Sign me in'), array('class' => 'btn bg-olive btn-block', 'ng-disabled' => 'loginform.$invalid'))}}                                                            
                        <p>
                            {{link_to('password/remind', trans('I forgot my password'))}}
                        </p>
                        <p>{{link_to('signup', trans('New user? Register now!'))}}</p>                    
                    </div>
                {{ Form::close() }}
            </div>
        </div>
@stop
@section('angular')
<script>
    var app = angular.module('app', []);
    app.controller('MainCtrl', ['$scope', function($scope) {
        $scope.email = '<?php echo $email ?>';
    }]);
</script>
@stop