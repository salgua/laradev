<!DOCTYPE html>
<html lang="en-US">
	<head>
		<meta charset="utf-8">
	</head>
	<body>
		<h2>Confirm your account</h2>

		<div>
			To confirm your account please click on the following link: {{ URL::to('confirm', array($confirmation_code)) }}.<br/>
		</div>
	</body>
</html>