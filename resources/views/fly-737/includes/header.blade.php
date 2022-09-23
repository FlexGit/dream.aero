@php
	/*if (!isset($city)) $city = null;

	$url = url('//' . env('DOMAIN_SITE', 'dream.aero'));
	if (Request::segment(1)) {
		$url .= '/' . Request::segment(1);
	}
	if (Request::segment(2)) {
		$url .= '/' . Request::segment(2);
	}*/
@endphp
<header class="header">
	<div class="flexy_row">
		<div>
			<a href="{{ url('/') }}" class="logo">
				<img src="{{ asset('img/' . Session::get('domain') . '/logo.webp') }}" alt="logo">
			</a>
		</div>
		<div style="width: 70px;"></div>
		<div class="main-menu">
			<ul>
				<li class="first active" id="mob">
					<a href="{{ url('/') }}">Home</a>
				</li>
				<ul>
					<li class="first">
						<a href="{{ url('about-simulator') }}">ABOUT THE SIMULATOR</a>
					</li>
					<li>
						<a href="{{ url('gift-sertificates') }}">GIFT VOUCHERS</a>
					</li>
					<li>
						<a href="{{ url('flight-options') }}">FLIGHT ROUTE OPTIONS</a>
					</li>
					<li>
						<a href="{{ url('prices') }}">PRICES</a>
					</li>
					<li class="last">
						<a href="{{ url('contacts') }}">CONTACT US</a>
					</li>
				</ul>
			</ul>
		</div>
		<div class="mobile-burger">
			<span></span><span></span><span></span>
		</div>
	</div>
</header>
