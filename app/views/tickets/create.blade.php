@extends('tickets.layouts.main')
@section('custom_css')
@stop

@section('header')
	
@stop

@section('content')
		<section class="content-header">
            <h1>
                {{{ Config::get('tickets.title') }}}
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
                            {{Form::hidden('email', Auth::user()->email)}}
	         				<div class="form-group">
                                <label for="subject">{{{ trans('Subject') }}} *</label>
                                {{Form::text('subject', '', array('class' => 'form-control', 'placeholder' => trans('Subject'), 'required' => '', 'ng-model' => 'subject'))}}
                            </div>
                            <div class="form-group">
                            	<label for="category">{{{ trans('Category') }}} *</label>
                            	{{Form::select('category', $categories, 1, array('class' => 'form-control', 'required' => '', 'ng-model' => 'category'))}}
                            </div>
                            <div class="form-group">
                            	<label for="description">{{{ trans('Description') }}} *</label>
                                {{ Form::textarea('description', '', array('class' => 'textarea', 'style' => 'width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;')) }}
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
    }]);
</script>
@stop