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
					<a href="{{ url($city->alias ?? '/') }}">Home</a>
				</li>
				<ul>
					<li class="first">
						<a href="{{ url($city->alias . '/about-simulator') }}">ABOUT THE SIMULATOR</a>
					</li>
					<li>
						<a href="{{ url($city->alias . '/gift-sertificates') }}">GIFT VOUCHERS</a>
					</li>
					<li>
						<a href="{{ url($city->alias . '/flight-options') }}">FLIGHT ROUTE OPTIONS</a>
					</li>
					<li>
						<a href="{{ url($city->alias . '/news') }}">NEWS</a>
					</li>
					@if ($city->alias == app('\App\Models\City')::UAE_ALIAS)
						<li>
							<a href="{{ url($city->alias . '/flight-briefing') }}">FLIGHT BRIEFING</a>
						</li>
					@endif
					@if ($city->alias == app('\App\Models\City')::DC_ALIAS)
						<li class="dropdownf">
							<a href="{{ url($city->alias . '/private-events') }}">PRIVATE EVENTS</a>
							<ul class="dropdown-menu">
								<li class="first">
									<a href="{{ url($city->alias . '/private-events#officeparties') }}">OFFICE PARTIES</a>
								</li>
								<li>
									<a href="{{ url($city->alias . '/private-events#birthday') }}">BIRTHDAY CELEBRATIONS</a>
								</li>
								<li class="last">
									<a href="{{ url($city->alias . '/private-events#socials') }}">SOCIALS</a>
								</li>
							</ul>
						</li>
					@endif
					<li class="dropdownf">
						<a href="{{ url($city->alias . '/prices') }}">PRICES</a>
						<ul class="dropdown-menu">
							<li class="first">
								<a href="{{ url($city->alias . '/prices#pran') }}">FLIGHTS</a>
							</li>
							<li>
								<a href="{{ url($city->alias . '/prices#courses') }}">COURSES</a>
							</li>
							<li>
								<a href="{{ url($city->alias . '/prices#sertan') }}">GIFT VOUCHERS</a>
							</li>
							<li class="last">
								<a href="{{ url($city->alias . '/prices#kpsch') }}">KIDS PILOT SCHOOL</a>
							</li>
						</ul>
					</li>
					<li>
						<a href="{{ url($city->alias . '/gallery') }}">GALLERY</a>
					</li>
					<li>
						<a href="{{ url($city->alias . '/reviews') }}">REVIEWS</a>
					</li>
					<li class="last">
						<a href="{{ url($city->alias . '/contacts') }}">CONTACT US</a>
					</li>
				</ul>
			</ul>
		</div>
		<div style="width: 70px;"></div>
		<div class="flexy_column nav">
			<div class="item">
				<p class="gl-current-select" id="city" data-toggle="modal" data-target="#city_modal">
					{{ $city->name }}
				</p>
			</div>
			<div>
				<span class="phone">
					@if($city->phone)
						<a href="javascript:void(0)" class="popup-with-form" data-popup-type="callback">
							{{ $city->phoneFormatted() }}
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
