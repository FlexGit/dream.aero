@php
	if (!isset($city)) $city = null;

	$url = url('//' . env('DOMAIN_SITE', 'dream.aero'));
	if (Request::segment(1)) {
		$url .= '/' . Request::segment(1);
	}
	if (Request::segment(2)) {
		$url .= '/' . Request::segment(2);
	}
@endphp
<header class="header">
	<div class="flexy_row">
		<div>
			<a href="{{ url(Request::session()->get('cityAlias') ?? '/') }}" class="logo">
				@if (App::isLocale('en'))
					<img src="{{ asset('img/logo-eng.png') }}" alt="logo">
				@else
					<img src="{{ asset('img/logo-new.webp') }}" alt="logo">
				@endif
			</a>
		</div>
		<div style="width: 70px;"></div>
		<div class="main-menu">
			<ul>
				<li class="first active" id="mob">
					<a href="{{ url(Request::session()->get('cityAlias') ?? '/') }}">Home</a>
				</li>
				<ul>
					<li class="first">
						<a href="{{ url(Request::session()->get('cityAlias') . '/about-simulator') }}">ABOUT THE SIMULATOR</a>
					</li>
					<li>
						<a href="{{ url(Request::session()->get('cityAlias') . '/gift-sertificates') }}">GIFT VOUCHERS</a>
					</li>
					<li>
						<a href="{{ url(Request::session()->get('cityAlias') . '/flight-options') }}">FLIGHT ROUTE OPTIONS</a>
					</li>
					<li>
						<a href="{{ url(Request::session()->get('cityAlias') . '/news') }}">NEWS</a>
					</li>
					<li class="dropdownf">
						<a href="{{ url(Request::session()->get('cityAlias') . '/private-events') }}">PRIVATE EVENTS</a>
						<ul class="dropdown-menu">
							<li class="first">
								<a href="{{ url(Request::session()->get('cityAlias') . '/private-events#officeparties') }}">OFFICE PARTIES</a>
							</li>
							<li>
								<a href="{{ url(Request::session()->get('cityAlias') . '/private-events#birthday') }}">BIRTHDAY CELEBRATIONS</a>
							</li>
							<li class="last">
								<a href="{{ url(Request::session()->get('cityAlias') . '/private-events#socials') }}">SOCIALS</a>
							</li>
						</ul>
					</li>
					<li class="dropdownf">
						<a href="{{ url(Request::session()->get('cityAlias') . '/prices') }}">PRICES</a>
						<ul class="dropdown-menu">
							<li class="first">
								<a href="{{ url(Request::session()->get('cityAlias') . '/prices#pran') }}">FLIGHTS</a>
							</li>
							<li>
								<a href="{{ url(Request::session()->get('cityAlias') . '/prices#courses') }}">COURSES</a>
							</li>
							<li>
								<a href="{{ url(Request::session()->get('cityAlias') . '/prices#sertan') }}">GIFT CERTIFICATES</a>
							</li>
							<li class="last">
								<a href="{{ url(Request::session()->get('cityAlias') . '/prices#kpsch') }}">KIDS PILOT SCHOOL</a>
							</li>
						</ul>
					</li>
					<li>
						<a href="{{ url(Request::session()->get('cityAlias') . '/gallery') }}">GALLERY</a>
					</li>
					<li>
						<a href="{{ url(Request::session()->get('cityAlias') . '/reviews') }}">REVIEWS</a>
					</li>
					<li class="last">
						<a href="{{ url(Request::session()->get('cityAlias') . '/contacts') }}">CONTACT US</a>
					</li>
				</ul>
			</ul>
		</div>
		<div style="width: 70px;"></div>
		<div class="flexy_column nav">
			<div class="item">
				<p class="gl-current-select" id="city" data-toggle="modal" data-target="#city_modal">
					@if(Request::session()->get('cityName'))
						{{ Request::session()->get('cityName') }}
					@else
						{{ app('\App\Models\City')::DEFAULT_CITY_NAME }}
					@endif
				</p>
			</div>
			<div>
				<span class="phone">
					<a href="tel:{{ $city->phone ?? '+1 240 224 48 85' }}">
						{{ ($city && $city->phone) ? $city->phoneFormatted() : '+1 240 224 48 85' }}
					</a>
				</span>
			</div>
		</div>
		<div class="mobile-burger">
			<span></span><span></span><span></span>
		</div>
	</div>
</header>
