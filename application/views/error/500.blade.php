<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

	<title>Internal Server Error - Womble</title>

	<meta name="author" content="Phill Sparks">
	<meta name="copyright" content="Copyright (c) Leicestershire County Scout Council. All rights reserved.">
	<meta name="generator" content="Laravel/2.0.4 (laravel.com)">
	<meta name="viewport" content="width=device-width, minimum-scale=1.0, maximum-scale=1.0">

	<link href="{{URL::to_asset('favicon.ico')}}" type="image/x-icon" rel="shortcut icon">
	<link href="{{URL::to_asset('css/womble.css')}}" rel="stylesheet" type="text/css">

	<!--[if IE]>
	<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
</head>
<body>

	<header class="navbar navbar-fixed"><div class="navbar-inner"><div class="container">
		<h1>{{HTML::link("/", "Womble", array('class' => 'brand'))}}</h1>
	</div></div></header>

	<div class="container">
		<section>

			<h1>Internal Server Error <small>Error 500</small></h1>

			<h2>What does this mean?</h2>

			<p>
				Something went wrong on our servers while we were processing your request.
				We're really sorry about this, and will work hard to get this resolved as
				soon as possible.
			</p>

			<p>
				Perhaps you would like to go to our {{HTML::link('/', 'home page')}}?
			</p>

		</section>
		<footer class="footer" id="bottom">
			<p class="pull-right"><a href="#">Back to top</a></p>
			<p>
				Made by <a href="http://phills.me.uk">Phill Sparks</a>.
				Powered by <a href="http://laravel.com">Laravel</a>.
				Styled with <a href="http://twitter.github.com/bootstrap/">Bootstrap, from Twitter</a>.
			</p>
		</footer>
	</div>

</body>
</html>
