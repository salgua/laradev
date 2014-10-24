<!DOCTYPE html>
<html lang="en-US">
	<head>
		<meta charset="utf-8">
	</head>
	<body>
		<h2>{{ sprintf(trans('Ticket %s updated'), $ticket->code) }}</h2>

		<div>
			<ul>
				<li>{{ trans('Author email') }}: {{$ticket->author_email }}</li>
				<li>{{ trans('Subjet') }}: {{$ticket->subject }}</li>
				<li>{{ trans('Category') }}: {{$ticket->category->title }}</li>
				<li>{{ trans('Assigned to') }}: {{$ticket->owner->getScreenName() }}</li>
				<li>{{ trans('Status') }}: 
						@if ($ticket->open)
                        	<span style="color: green">{{ trans('open') }}</span>
                        @else
                            <span style="color: red">{{ trans('closed') }}</span>
                        @endif
				</li>
			</ul>
		</div>
		<div>
			@if (count($ticket->comments()->get()))
				<h2>{{trans('Last comment')}}</h2>
				<ul>
					<?php $comment = $ticket->comments()->orderBy('created_at', 'desc')->first(); ?>
					<li>{{ trans('Comment author')}}: {{$comment->author_email}}</li>
					<li>{{ trans('Created at')}}: {{$comment->created_at}}</li>

				</ul>
				<p>{{$comment->description}}</p>
			@endif
			<p>{{trans('You can view the ticket at the following url')}}: {{ URL::to('tickets/code', array($ticket->code)) }}</p>
		</div>
	</body>
</html>