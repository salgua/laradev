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
         					<h3 class="box-title">Ticket #{{{ $ticket->code }}} / {{{ $ticket->created_at }}}</h3>
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
                              </dl>
                              <div class="ticket-description">
                                 {{ $ticket->description }}
                              </div>
                           </div>
                        </div>
            				<div class="row">
                           <div class="col-md-6">
                  				<dl>
                  					<dt>{{{trans('Category')}}}</dt>
                  					<dd>{{{ $ticket->category()->first()->title }}}</dd>
                  				</dl>
                              <dl>
                                 <dt>{{{ trans('Author') }}}</dt>
                                 <dd>{{{ $ticket->author_email }}}</dd>
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
                                 <dt>{{{ trans('Assigned to') }}}</dt>
                                 <dd>{{{ $ticket->owner->getScreenName() }}}</dd>
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
                           <div class="row">
                           <div class="col-md-4"> 
                           {{ Form::open(array('url' => 'tickets/close', 'novalidate' => '', 'name' => 'form')) }}
                           {{ Form::hidden('id', $ticket->id) }}
                           <div class="form-group">
                           {{ Form::submit(trans('Close ticket'), array('class' => 'btn btn-danger')) }}
                           </div>
                           {{ Form::close()}}
                           </div>
                           
                           {{ Form::open(array('url' => 'tickets/change', 'novalidate' => '', 'name' => 'formowner')) }}
                           {{ Form::hidden('id', $ticket->id) }}
                           <div class="col-md-4"> 
                           <div class="form-group">
                              {{Form::select('owner', $owners, 1, array('class' => 'form-control'))}}
                           </div>
                           </div>
                           <div class="col-md-4"> 
                              {{ Form::submit(trans('Change owner'), array('class' => 'btn btn-success')) }}
                           </div>
                           {{ Form::close()}}
                           </div>
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
                                 <img src="{{ getAvatar($comment->author_email) }}" alt="">
                                 <div class="message">
                                    <span class="name">{{$comment->getAuthorScreenName()}} <small class="text-muted pull-right"><i class="fa fa-clock-o"></i> {{$comment->created_at}}</small></span>
                                    {{$comment->description}}
                                 </div>
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
                                    {{ Form::textarea('description', '', array('class' => 'textarea', 'style' => 'width: 100%; height: 100px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;')) }}
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
   $(".textarea").wysihtml5({"font-styles": false});
    var app = angular.module('app', ['ui.utils', 'remoteValidation']);
    app.controller('MainCtrl', ['$scope', '$http', function($scope, $http) {
        
    }]);
</script>
@stop