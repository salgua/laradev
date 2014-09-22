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
                            	{{ Form::textarea('description', '', array('class' => 'form-control', 'required' => '', 'ng-model' => 'description', 'rows' => '3')) }}
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
    var app = angular.module('app', ['ui.utils', 'remoteValidation']);
    app.controller('MainCtrl', ['$scope', '$http', function($scope, $http) {
    	$scope.category = 1;
    }]);
</script>
@stop