<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
	<title>
		@yield('title')
	</title>
	<meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1,user-scalable=no">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<style>
		@page {
			margin: 20px;
			sheet-size: 300px 250mm;
		}
		body {
			font-size: 11px;
			font-family: DejaVuSans;
		}
	</style>
	<!-- CSS -->
	@stack('css')
	<!-- END CSS -->
</head>
<body>
	<div class="content">
		@yield('content')
	</div>
</body>
</html>
