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
				<img src="{{ asset('img/logo-dreamaero.webp') }}" alt="logo">
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
					@if (Request::session()->get('cityAlias') == app('\App\Models\City')::UAE_ALIAS)
						<li>
							<a href="{{ url(Request::session()->get('cityAlias') . '/flight-briefing') }}">FLIGHT BRIEFING</a>
						</li>
					@endif
					@if (Request::session()->get('cityAlias') == app('\App\Models\City')::DC_ALIAS)
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
					@endif
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
								<a href="{{ url(Request::session()->get('cityAlias') . '/prices#sertan') }}">GIFT VOUCHERS</a>
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
					{{ Request::session()->get('cityName') }}
				</p>
			</div>
			<div>
				<span class="phone">
					@if(Request::session()->get('cityPhone'))
						<a href="javascript:void(0)" class="popup-with-form" data-popup-type="callback">
							{{ Request::session()->get('cityPhone') }}
						</a>
					@endif
				</span>
			</div>
		</div>
		<div class="mobile-burger">
			<span></span><span></span><span></span>
		</div>
	</div>
</header>
