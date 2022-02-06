<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
	<title>Khosomatak</title>
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
	<meta name="HandheldFriendly" content="true">
	<meta name="apple-touch-fullscreen" content="yes">

    <!-- Google Fonts-->
	<link href="https://fonts.googleapis.com/css?family=Nunito:300,300i,400,400i,600,600i,700,700i,800" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}" type="text/css" media="all">
    <style type="text/css">
		body{ font-family:'Nunito'; font-weight:400; font-size:18px; }
		table tr td:first-child{ width:200px; }
	</style>
	
</head>
<body>
    <p> Hello, </p>
    <p> Greetings from {{ config('app.name', '') }} !!</p>
    <p> Your password has been changed for further login process.</p>
    <p> Please copy below password to login at {{ config('app.name', '') }} App.</p>
    <p> Your password is <b>{{ $maildetails['password']  }}</b></p>
    <p>With Regards,<br/>
    {{ config('app.name', '') }} Team.</p>
</body>
</html>