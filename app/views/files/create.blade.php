@extends('layouts.main')
@section('custom_css')

@stop

@section('header')
	
@stop

@section('content')
<section class="content-header">
    <h1>
        {{{ trans('test file upload') }}}
    </h1>
 </section>
 <section class="content" ng-app="app" ng-controller="MainCtrl as main">
         	<div class="row">
         		<div class="col-md-8">
         			<div class="box box-primary">
         				<div class="box-header">
         					<h3 class="box-title">{{{ trans('New file form') }}}</h3>
         				</div>
         				{{ Form::open(['route' => 'files.store', 'files' => true, 'novalidate' => '', 'name' => 'form']) }}
         				<div class="box-body">
	         				<div class="form-group">
                                <label for="subject">{{{ trans('File') }}} *</label>
                                {{Form::file('attachment', array('class' => 'form-control', 'required' => '', 'valid-file' => '', 'ng-model' => 'attachment'))}}
                            </div>
         				</div>
         				<div class="box-footer">
                            {{Form::submit(trans('Submit'), array('class' => 'btn btn-primary', 'ng-disabled' => 'form.$invalid'))}}
                        </div>
         				{{ Form::close() }}
         			</div>
         		</div>
         	</div>
         </section>
@stop

@section('angular')
<script>
    //$(".textarea").wysihtml5({"font-styles": false});
    var app = angular.module('app', ['ui.utils', 'remoteValidation']);
    app.controller('MainCtrl', ['$scope', '$http', function($scope, $http) {

    }]);
    app.directive('validFile', function () {
	    return {
		        require: 'ngModel',
		        link: function (scope, el, attrs, ngModel) {
		            ngModel.$render = function () {
		                ngModel.$setViewValue(el.val());
		            };

		            el.bind('change', function () {
		                scope.$apply(function () {
		                    ngModel.$render();
		                });
		            });
		        }
	    	};
	});
</script>
@stop