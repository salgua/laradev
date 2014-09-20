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
                  @if ($ticket->isManager())
                  <div class="box box-primary">
                     <div class="box-header">
                        <h3 class="box-title">{{trans('Ticket manager actions')}}</h3>
                     </div>
                     <div class="box-body">
                        @if ($ticket->open)
                           {{ Form::open(array('url' => 'tickets/close', 'novalidate' => '', 'name' => 'form')) }}
                           {{ Form::hidden('id', $ticket->id) }}
                           {{ Form::submit(trans('Close ticket'), array('class' => 'btn btn-danger')) }}
                           {{ Form::close()}}
                        @else
                           {{ Form::open(array('url' => 'tickets/open', 'novalidate' => '', 'name' => 'form')) }}
                           {{ Form::hidden('id', $ticket->id) }}
                           {{ Form::submit(trans('Reopen ticket'), array('class' => 'btn btn-success')) }}
                           {{ Form::close()}} 
                        @endif                       
                     </div>
                  </div>
                  @endif
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
                           @if (Auth::check() && $ticket->open)
                           {{ Form::open(array('url' => 'tickets/comment', 'novalidate' => '', 'name' => 'form')) }}
                           <div class="box-footer">
                                 {{ Form::hidden('ticket', $ticket->id) }}
                                 {{ Form::hidden('email', Auth::user()->email )}}

                                 <div class="form-group">
                                    <label for="description">{{{ trans('Comment') }}} *</label>
                                    {{ Form::textarea('description', '', array('class' => 'form-control', 'required' => '', 'ng-model' => 'description', 'rows' => '2')) }}
                                  </div>
                              {{Form::submit(trans('Submit'), array('class' => 'btn btn-primary', 'ng-disabled' => 'form.$invalid'))}}
                           </div>
                           {{ Form::close() }}
                           @elseif (Auth::check())
                           <div class="box-footer">
                              <p>{{trans('Comments for this ticket are closed')}}</p>
                           </div>
                           @else
                           <div class="box-footer">
                              <p>{{trans('You must be logged in comment this ticket!')}}</p>
                              <p>{{link_to('login/'.$email, trans('login'))}} - {{trans('New user?')}} {{link_to('signup/'.$email, trans('register now'))}}</p>
                           </div>
                           @endif
                     </div>
                  </div>
               </div>
         </section>
@stop
@section('angular')
<script>
    var app = angular.module('app', ['ui.utils', 'remoteValidation']);
    app.controller('MainCtrl', ['$scope', '$http', function($scope, $http) {
        
    }]);
</script>
@stop