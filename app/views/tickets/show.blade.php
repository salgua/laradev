@extends('tickets.layouts.main')
@section('custom_css')
@stop

@section('header')
	
@stop

@section('content')
         <section class="content" ng-app="app" ng-controller="MainCtrl as main">
         <div class="row">
         		<div class="col-md-6">
         			<div class="box box-primary">
         				<div class="box-header">
         					<h3 class="box-title">Ticket #{{{ $ticket->code }}}</h3>
         				</div>
         				<div class="box-body">
                        <div class="row">
                           <div class="col-md-12">
                              <dl>
                                 <dt>{{{trans('Subject')}}}</dt>
                                 <dd>{{{ $ticket->subject }}}</dd>
                              </dl>
                              <dl>
                                 <dt>{{{trans('Description')}}}</dt>
                                 <dd>{{{ $ticket->description }}}</dd>
                              </dl>
                           </div>
                        </div>
            				<div class="row">
                           <div class="col-md-6">
                  				<dl>
                  					<dt>{{{trans('Category')}}}</dt>
                  					<dd>{{{ $ticket->category()->first()->title }}}</dd>
                  				</dl>
                              <dl>
                                 <dt>{{{trans('Creation date')}}}</dt>
                                 <dd>{{{ $ticket->created_at }}}</dd>
                              </dl>
                           </div>
                           <div class="col-md-6">
                              <dl>
                                 <dt>{{{trans('Status')}}}</dt>
                                 <dd>
                                       @if ($ticket->open)
                                          <span class="text-green">{{ trans('open') }}</span>
                                       @else
                                          <span class="text-red">{{ trans('closed') }}</span>
                                       @endif
                                 </dd>
                              </dl>
                              <dl>
                                 <dt>{{{ trans('Author') }}}</dt>
                                 <dd>{{{ $ticket->author_email }}}</dd>
                              </dl>
                           </div>
            				</div>
         				</div>
         			</div>
         		</div>
               <div class="col-md-6">
                  <div class="box box-warning">
                     <div class="box-header">
                        <h3 class="box-title">{{trans('Comments')}}</h3>
                     </div>
                     <div class="box-body chat">
                        <?php $comments =  $ticket->comments()->get(); ?>
                        @if (count($comments))
                           @foreach ($comments as $comment)
                              <div class="item">
                                 <img src="{{asset('img/avatar.png')}}" alt="">
                                 <p class="message">
                                    <span class="name">{{$comment->author_email}}</span>
                                    {{$comment->description}}
                                 </p>
                              </div>
                           @endforeach
                        @else
                           <p>{{trans("There aren't comments.")}}</p>
                        @endif
                     </div>
                           {{ Form::open(array('url' => 'tickets/comment', 'novalidate' => '', 'name' => 'form')) }}
                           <div class="box-footer">
                                 {{ Form::hidden('ticket', $ticket->id) }}
                                 <div class="form-group has-feedback" ng-class="{ 'has-error': form.email.$invalid && form.email.$dirty }">
                                 <label for="email">{{trans('Email address')}} *</label>
                                 {{Form::email('email', '', array('class' => 'form-control', 'placeholder' => 'Email', 'required' => '', 'ng-model' => 'email'))}}
                                 <span ng-show="ticketform.email.$invalid && ticketform.email.$dirty" class="help-block">{{trans('Insert a valid email address')}}</span>
                                 </div>
                                 <div class="form-group">
                                    <label for="description">{{{ trans('Comment') }}} *</label>
                                    {{ Form::textarea('description', '', array('class' => 'form-control', 'required' => '', 'ng-model' => 'description', 'rows' => '2')) }}
                                  </div>
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
    var app = angular.module('app', ['ui.utils', 'remoteValidation']);
    app.controller('MainCtrl', ['$scope', '$http', function($scope, $http) {
         $scope.email = '<?php echo $email; ?>';
    }]);
</script>
@stop