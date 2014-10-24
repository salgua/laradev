<!DOCTYPE html>
<html lang="en-US">
	<head>
		<meta charset="utf-8">
	</head>
	<body>
		<h2>{{ sprintf(trans('Hello %s,'), $target->screen_name) }}</h2>

		<div>
			<p>{{ sprintf(trans('There\'s a ticket which has been <span style="color: green">open</span> for %s days!'), $delay) }}</p>
			<p>{{ trans('We need to respond promply to our customer, else they will go away!') }}</p>
			<p>{{ trans('You can view the ticket at the following url')}}: {{ URL::to('tickets/code', array($ticket->code)); }}
			<p>{{ sprintf(trans('The ticket has been sent from %s.'), $ticket->author_email) }}</p>
			<p>{{ sprintf(trans('With suject: "%s".'), $ticket->subject) }}</p>
			<p>{{ sprintf(trans('The full content of the message is: %s'), $ticket->description) }}</p></p>
		</div>
	</body>
</html>