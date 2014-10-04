@extends('admin.layouts.main')
@section('content')
    <div ng-app="app">
            <div class="form-box" id="login-box" ng-controller="MainCtrl as main">
                <div class="header">{{{trans('New user registration')}}}</div>
                {{ Form::open(array('url' => 'signup', 'novalidate' => '', 'name' => 'signupform')) }}
                    <div class="body bg-gray">
                        <div class="form-group has-feedback" ng-class="{ 'has-error': signupform.email.$invalid && signupform.email.$dirty }">
                            {{Form::email('email', '', array('class' => 'form-control', 'placeholder' => 'Email', 'required' => '', 'ng-model' => 'email', 'ng-remote-validate' => 'api/check-email', 'ng-remote-method' => 'GET'))}}
                            <span ng-show="!signupform.email.$error.ngRemoteValidate && signupform.email.$invalid && signupform.email.$dirty" class="help-block">{{trans('Insert a valid email address')}}</span>
                            <span ng-show="signupform.email.$error.ngRemoteValidate" class="help-block">{{trans('The email address is already registered, ').link_to('login', trans('log in'))}}</span>
                        </div>
                        <div class="form-group has-feedback" ng-class="{ 'has-error': signupform.password.$invalid && signupform.password.$dirty }">
                            <input name="password" type="password" class="form-control" placeholder="Password" required ng-model="password" minlength="8">
                            <span ng-show="signupform.password.$invalid && signupform.password.$dirty" class="help-block">{{trans('Password should have at least 8 characters')}}</span>
                        </div>
                        <div class="form-group has-feedback" ng-class="{ 'has-error': signupform.password_confirmation.$invalid && signupform.password_confirmation.$dirty }">
                            <input name="password_confirmation" type="password" class="form-control" placeholder="{{ trans('Retype password') }}" required ng-model="password_confirmation" ui-validate=" '$value==password' " ui-validate-watch=" 'password' ">
                            <span ng-show="signupform.password_confirmation.$error.validator" class="help-block">{{trans('Fields are not equal!')}}</span>
                        </div>          
                    </div>
                    <div class="footer">   
                        {{Form::submit(trans('Submit'), array('class' => 'btn bg-olive btn-block', 'ng-disabled' => 'signupform.$invalid'))}}                                                                              
                    </div>
                {{ Form::close() }}
            </div>
        </div>
@stop
@section('angular')
<script>
    var app = angular.module('app', ['ui.utils', 'remoteValidation']);
    app.controller('MainCtrl', ['$scope', '$http', function($scope, $http) {
        $scope.email = '<?php echo $email; ?>';
    }]);

</script>
@stop