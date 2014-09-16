@extends('admin.layouts.main')
@section('content')
    <div ng-app="app">
            <div class="form-box" id="login-box" ng-controller="MainCtrl as main">
                <div class="header">{{{trans('Password reset')}}}</div>
                {{ Form::open(array('url' => 'password/reset', 'novalidate' => '', 'name' => 'resetform')) }}
                {{ Form::hidden('token', $token)}}
                    <div class="body bg-gray">
                        <div class="form-group has-feedback" ng-class="{ 'has-error': resetform.email.$invalid && resetform.email.$dirty }">
                            {{Form::email('email', '', array('class' => 'form-control', 'placeholder' => 'Email', 'required' => '', 'ng-model' => 'email'))}}
                            <span ng-show="resetform.email.$invalid && resetform.email.$dirty" class="help-block">{{trans('Insert a valid email address')}}</span>
                        </div>
                        <div class="form-group has-feedback" ng-class="{ 'has-error': resetform.password.$invalid && resetform.password.$dirty }">
                            <input name="password" type="password" class="form-control" placeholder="Password" required ng-model="password">
                        </div>
                        <div class="form-group has-feedback" ng-class="{ 'has-error': resetform.password_confirmation.$invalid && resetform.password_confirmation.$dirty }">
                            <input name="password_confirmation" type="password" class="form-control" placeholder="{{ trans('Retype password') }}" required ng-model="password_confirmation" ui-validate=" '$value==password' " ui-validate-watch=" 'password' ">
                            <span ng-show="resetform.password_confirmation.$error.validator" class="help-block">{{trans('Fields are not equal!')}}</span>
                        </div>          
                    </div>
                    <div class="footer">   
                        {{Form::submit(trans('Submit'), array('class' => 'btn bg-olive btn-block', 'ng-disabled' => 'resetform.$invalid'))}}                                                                              
                    </div>
                {{ Form::close() }}
            </div>
        </div>
@stop
@section('angular')
<script>
    var app = angular.module('app', ['ui.utils']);
    app.controller('MainCtrl', ['$scope', function($scope) {

    }]);

</script>
@stop