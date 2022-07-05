@php
	if (!isset($city)) $city = null;

	$url = url('//' . env('DOMAIN_SITE', 'dream-aero.ru'));
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
		<div class="main-menu">
			<ul>
				<li class="first active" id="mob">
					<a href="{{ url(Request::session()->get('cityAlias') ?? '/') }}">@lang('main.верхнее-меню.главная')</a>
				</li>
				<ul>
					<li class="first dropdownf"><a href="{{ url('o-trenazhere') }}">@lang('main.верхнее-меню.о-тренажере')</a>
						<ul class="dropdown-menu">
							<li class="first">
								<a href="{{ url('virtualt') }}">@lang('main.верхнее-меню.виртуальный-тур-b737')</a>
							</li>
							<li class="last">
								<a href="{{ url('virtualt-airbus') }}">@lang('main.верхнее-меню.виртуальный-тур-a320')</a>
							</li>
						</ul>
					</li>
					<li>
						<a href="{{ url('podarit-polet') }}">@lang('main.верхнее-меню.подарить-полет')</a>
					</li>
					<li>
						<a href="{{ url('variantyi-poleta') }}">@lang('main.верхнее-меню.варианты-полета')</a>
					</li>
					<li>
						<a href="{{ url('news') }}" >@lang('main.верхнее-меню.новости')</a>
					</li>
					<li class="dropdownf">
						<a href="{{ url('instruktazh') }}">@lang('main.верхнее-меню.инструктаж')</a>
						<ul class="dropdown-menu">
							<li class="first">
								<a href="{{ url('instruktazh/boeing-737-ng') }}">Boeing 737 NG</a>
							</li>
							<li class="last">
								<a href="{{ url('instruktazh/airbus-a320') }}">Airbus A320</a>
							</li>
						</ul>
					</li>
					<li class="dropdownf">
						<a href="{{ url(Request::session()->get('cityAlias') ? Request::session()->get('cityAlias') . '/price' : 'price') }}">@lang('main.верхнее-меню.цены')</a>
						<ul class="dropdown-menu">
							<li class="first">
								<a href="{{ url('vse-akcii') }}">@lang('main.верхнее-меню.акции')</a>
							</li>
						</ul>
					</li>
					<li class="dropdownf">
						<a href="{{ url('galereya') }}">@lang('main.верхнее-меню.галерея')</a>
						<ul class="dropdown-menu">
							<li class="first">
								<a href="{{ url('galereya/#ourguestes') }}">@lang('main.верхнее-меню.наши-гости')</a>
							</li>
						</ul>
					</li>
					<li>
						<a href="{{ url('reviews') }}">@lang('main.верхнее-меню.отзывы')</a>
					</li>
					<li class="last">
						<a href="{{ url(Request::session()->get('cityAlias') ? Request::session()->get('cityAlias') . '/contacts' : 'contacts') }}">@lang('main.верхнее-меню.контакты')</a>
					</li>
				</ul>
			</ul>
		</div>
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
					<a href="tel:{{ $city->phone ?? '+74955328737' }}">
						{{ ($city && $city->phone) ? $city->phoneFormatted() : '+7 (495) 532-87-37' }}
					</a>
				</span>
			</div>
		</div>
		<div class="mobile-burger">
			<span></span><span></span><span></span>
		</div>
	</div>
</header>
