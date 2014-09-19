<!DOCTYPE html>
<html lang="en-US">
	<head>
		<meta charset="utf-8">
	</head>
	<body>
		<h2>{{ trans('Thank you for your ticket') }}</h2>

		<div>
			{{ trans("We received your ticket. We will elaborate it as soon as possible and we will give you a response to this email address. If you want to check your ticket status please click on the following link:") }}<br/>
			{{ URL::to('tickets/code', array($ticket_code, $author_email)) }}<br/>
		</div>
	</body>
</html>