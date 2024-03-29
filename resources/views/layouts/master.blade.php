<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
	<title>
		@yield('title')
	</title>
	<meta name="description" content="@yield('description')">
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
	<link rel="shortcut icon" type="image/png" href="{{ asset('img/favicon.png') }}" />
	<link rel="shortcut icon" type="image/svg+xml" href="{{ asset('img/favicon.svg') }}" />
	<link rel="canonical" href="{{ url()->current() }}">
	<meta name="google-site-verification" content="BHdHLHHg2mdgdi0sHcNT9Ng5yp2zThE-tl1tXxZZiGk" />
	<meta name="yandex-verification" content="26119517b8383ec4" />
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<!-- CSS -->
	@if(request()->getHost() == env('DOMAIN_SITE2'))
		@include('fly-737.includes.css')
	@else
		@include('dream-aero.includes.css')
	@endif
	@stack('css')
	<!-- END CSS -->
</head>
<body>
	<!-- HEADER -->
	@if(request()->getHost() == env('DOMAIN_SITE2'))
		@include('fly-737.includes.header')
	@else
		@include('dream-aero.includes.header')
	@endif
	<!-- END HEADER -->
	<div class="content">
		@yield('content')
	</div>
	<!-- FOOTER -->
	@if(request()->getHost() == env('DOMAIN_SITE2'))
		@include('fly-737.includes.footer')
	@else
		@include('dream-aero.includes.footer')
	@endif
	<!-- END FOOTER -->
	<!-- JS -->
	@if(request()->getHost() == env('DOMAIN_SITE2'))
		@include('fly-737.includes.js')
	@else
		@include('dream-aero.includes.js')
	@endif
	@stack('scripts')
	<!-- END JS -->
</body>
</html>
