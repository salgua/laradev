@extends('tickets.layouts.main')
@section('custom_css')
@stop

@section('header')
	
@stop

@section('content')
		<section class="content-header">
            <h1>
                {{{ Config::get('tickets.title') }}}
                <small>{{{ trans('guest area') }}}</small>
            </h1>
         </section>
         <section class="content" ng-app="app" ng-controller="MainCtrl as main">
         	<div class="row">
         		<div class="col-md-8">
         			<div class="box box-primary">
         				<div class="box-header">
         					<h3 class="box-title">{{{ trans('Support ticket form') }}}</h3>
         				</div>
         				{{ Form::open(array('url' => 'tickets/save', 'novalidate' => '', 'name' => 'ticketform')) }}
         				<div class="box-body">
	         				<div class="form-group has-feedback" ng-class="{ 'has-error': ticketform.email.$invalid && ticketform.email.$dirty }">
	         					<label for="email">{{trans('Email address')}} *</label>
	         					{{Form::email('email', Input::old('email'), array('class' => 'form-control', 'placeholder' => 'Email', 'required' => '', 'ng-model' => 'email'))}}
	         					<span ng-show="ticketform.email.$invalid && ticketform.email.$dirty" class="help-block">{{trans('Insert a valid email address')}}</span>
	         				</div>
	         				<div class="form-group">
                                <label for="subject">{{{ trans('Subject') }}} *</label>
                                {{Form::text('subject', Input::old('subject'), array('class' => 'form-control', 'placeholder' => trans('Subject'), 'required' => '', 'ng-model' => 'subject'))}}
                            </div>
                            <div class="form-group">
                            	<label for="category">{{{ trans('Category') }}} *</label>
                            	{{Form::select('category', $categories, 1, array('class' => 'form-control', 'required' => '', 'ng-model' => 'category'))}}
                            </div>
                            <div class="form-group">
                            	<label for="description">{{{ trans('Description') }}} *</label>
                            	{{ Form::textarea('description', Input::old('description'), array('class' => 'textarea', 'style' => 'width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;')) }}
                            </div>
                                <div class="checkbox-agreement">
                                    <label> 
                                    {{ Form::checkbox('agreement', 'ok', false, array('id' => 'agreement', 'required' => '', 'ng-model' => 'agreement.checked')) }} &nbsp;
                                        {{ trans('I agree to the treatment of my personal data') }}
                                    </label>
                                </div>
         				</div>
         				<div class="box-footer">
                            {{Form::submit(trans('Submit'), array('class' => 'btn btn-primary', 'ng-disabled' => 'ticketform.$invalid'))}}
                        </div>
         				{{ Form::close() }}
         			</div>
         		</div>
         	</div>
         </section>
@stop

@section('angular')
<script>
    $(".textarea").wysihtml5({"font-styles": false});
    var app = angular.module('app', ['ui.utils', 'remoteValidation']);
    app.controller('MainCtrl', ['$scope', '$http', function($scope, $http) {
    	$scope.category = '<?php echo Input::old("category", 1); ?>';
        $scope.subject = '<?php echo Input::old("subject"); ?>';
        $scope.email = '<?php echo Input::old("email"); ?>';
    }]);
</script>
@stop