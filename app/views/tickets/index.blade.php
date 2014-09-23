@extends('tickets.layouts.main')
@section('custom_css')
@stop

@section('header')
	
@stop

@section('content')
<section class="content" ng-app="app" ng-controller="MainCtrl as main">
	<div class="row">
		<div class="col-md-12">
      
			<div class="box box-primary">
				<div class="box-header">
         			<h3 class="box-title">{{trans('My tickets')}}</h3>
         		</div>
         		<div class="box-body table-responsive">
                  @if (count($tickets))
         			<table class="table table-bordered">
         				<tbody>
         					<tr>
         						<th>
         							#
         						</th>
         						<th>{{trans('Date')}}</th>
         						<th>{{trans('Author')}}</th>
         						<th>{{trans('Subject')}}</th>
         						<th>{{trans('Category')}}</th>
         						<th>{{trans('Assigned to')}}</th>
         						<th>{{trans('Status')}}</th>
								<th>{{trans('Comments')}}</th>
         					</tr>
         					@foreach ($tickets as $ticket)
         						<tr>
         							<td>{{link_to('tickets/show/'.$ticket->code,$ticket->code)}}</td>
         							<td>{{$ticket->created_at}}</td>
         							<td>{{$ticket->author_email}}</td>
         							<td>{{$ticket->subject}}</td>
         							<td>{{$ticket->category()->first()->title}}</td>
         							<td>{{$ticket->owner()->first()->email}}</td>
         							<td>@if ($ticket->open)
                                          <span class="text-green">{{ trans('open') }}</span>
                                       @else
                                          <span class="text-red">{{ trans('closed') }}</span>
                                       @endif
                                    </td>
                                    <td>{{count($ticket->comments()->get())}}</td>
         						</tr>
         					@endforeach
         				</tbody>
         			</table>
                  @else
                  <p>{{{trans("You don't have any tickets")}}}</p>
                  @endif
         		</div>
			</div>

		</div>
	</div>
</section>
@stop