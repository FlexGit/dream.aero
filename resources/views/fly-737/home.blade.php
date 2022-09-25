@extends('layouts.master')

@section('title')
	{{ $page->meta_title }}
@stop
@section('description', $page->meta_description)

@section('content')
	<div class="main-block-full str">
		<div class="video">
			<video poster="{{ asset('img/' . Session::get('domain') . '/mainvideo.webp') }}" preload="auto" muted playsinline autoplay="autoplay" loop="loop">
				<source src="{{ asset('video/' . Session::get('domain') . '/mainvideo.mp4?v=2') }}" type="video/mp4">
				<img src="{{ asset('img/' . Session::get('domain') . '/mainvideo.webp') }}" alt="" width="100%" height="100%">
			</video>
		</div>

		<div class="container conthide">
			<h1 class="wow fadeInDown animated" data-wow-duration="2s" data-wow-delay="0.3s" data-wow-iteration="1">TEST YOURSELF IN THE COCKPIT</h1>
			<span class="wow fadeInDown" data-wow-duration="2s" data-wow-delay="0.9s" data-wow-iteration="1">Welcome to the Boeing 737 NG* Flight Simulator of the famous Boeing airliner!</span>
			{{--<a href="{{ url('#popup') }}" class="give button-pipaluk button-pipaluk-orange wow zoomIn popup-with-form form_open" data-modal="certificate" data-wow-delay="1.3s" data-wow-duration="2s" data-wow-iteration="1"><i>Buy Now</i></a>--}}
		</div>
		<div class="scroll-down">
			<a class="scrollTo" href="#about">
				<svg class="t-cover__arrow-svg" style="fill: #f9fbf2;" x="0px" y="0px" width="38.417px" height="18.592px" viewBox="0 0 38.417 18.592"><g><path d="M19.208,18.592c-0.241,0-0.483-0.087-0.673-0.261L0.327,1.74c-0.408-0.372-0.438-1.004-0.066-1.413c0.372-0.409,1.004-0.439,1.413-0.066L19.208,16.24L36.743,0.261c0.411-0.372,1.042-0.342,1.413,0.066c0.372,0.408,0.343,1.041-0.065,1.413L19.881,18.332C19.691,18.505,19.449,18.592,19.208,18.592z"></path></g></svg>
			</a>
		</div>
	</div>

	<div class="about" id="about">
		<div class="container">
			<h2 class="block-title">BOEING 737 NG* FLIGHT SIMULATOR</h2>
			<div class="text-block">
				<p>
					We are pleased to introduce Full Motion flight simulators of the famous passenger airliner in the US. Designed as a real plane cockpit, this simulator is analogous to the simulators used for training real-life pilots. Test the pilot in you!
					<a href="{{ url('about-simulator') }}" class="button-pipaluk button-pipaluk-white"><i>@lang('main-fly737.home.подробнее')</i></a>
				</p>
			</div>
		</div>
		<div class="image">
			<img src="{{ asset('img/' . Session::get('domain') . '/about-bg.webp') }}" alt="" width="100%" height="auto">
		</div>
	</div>

	<div class="obtain">
		<div class="container">
			<h3>@lang('main-fly737.home.что-вы-получите')</h3>
			<ul class="row">
				<li class="col-md-3 col-sm-6 wow fadeInUp" data-wow-delay="0.5s" data-wow-duration="2s">
					<a href="{{ url('impressions') }}">
						<img src="{{ asset('img/' . Session::get('domain') . '/airplane-shape.webp') }}" alt="" width="56" height="auto">
						<span>An unforgettable experience</span>
					</a>
				</li>
				<li class="col-md-3 col-sm-6 wow fadeInUp" data-wow-delay="0.5s" data-wow-duration="2s">
					<a href="{{ url('prof-assistance') }}">
						<img src="{{ asset('img/' . Session::get('domain') . '/pilot-hat.webp') }}" alt="" width="66" height="auto">
						<span>The guidance of a pilot instructor</span>
					</a>
				</li>
				<li class="col-md-3 col-sm-6 wow fadeInUp" data-wow-delay="0.5s" data-wow-duration="2s">
					<a href="{{ url('the-world-of-aviation') }}">
						<img src="{{ asset('img/' . Session::get('domain') . '/pilot.webp') }}" alt="" width="61" height="auto">
						<span>Immersion in the world of aviation</span>
					</a>
				</li>
				<li class="col-md-3 col-sm-6 wow fadeInUp" data-wow-delay="0.5s" data-wow-duration="2s">
					<a href="{{ url('treating-aerophobia') }}">
						<img src="{{ asset('img/' . Session::get('domain') . '/cloud.webp') }}" alt="" width="61" height="auto">
						<span>Treating aerophobia</span>
					</a>
				</li>
			</ul>
			{{--<a href="{{ url('#popup') }}" class="obtain-button button-pipaluk button-pipaluk-orange popup-with-form form_open" data-modal="booking"><i>Buy Now</i></a>--}}
		</div>
	</div>

	<div class="facts pages" id="home" data-type="background" data-speed="20">
		<div class="container">
			<h2 class="block-title">@lang('main-fly737.home.несколько-фактов-о-нас')</h2>
			<ul class="row">
				<li class="col-md-3 wow">
					<div class="ico">
						<img src="{{ asset('img/' . Session::get('domain') . '/facts-ico1.webp') }}" alt="" width="41" height="">
					</div>
					<div class="wow fadeInUp" data-wow-delay="0" data-wow-duration="2s">
						<span>@lang('main-fly737.home.динамическая-платформа')</span>
						<p>@lang('main-fly737.home.устройство-представляет-собой-подвижную-систему')</p>
					</div>
				</li>
				<li class="col-md-3 wow">
					<div class="ico">
						<img src="{{ asset('img/' . Session::get('domain') . '/facts-ico4.webp') }}" alt="" width="40" height="">
					</div>
					<div class="wow fadeInUp" data-wow-delay="0" data-wow-duration="2s">
						<span>@lang('main-fly737.home.визуализация-и-ощущения')</span>
						<p>@lang('main-fly737.home.панорамное-остекление-кабины')</p>
					</div>
				</li>
				<li class="col-md-3 wow">
					<div class="ico">
						<img src="{{ asset('img/' . Session::get('domain') . '/facts-ico3.webp') }}" alt="" width="42" height="">
					</div>
					<div class="wow fadeInUp" data-wow-delay="0" data-wow-duration="2s">
						<span>@lang('main-fly737.home.оборудование-и-приборы')</span>
						<p>@lang('main-fly737.home.все-приборы-настоящие')</p>
					</div>
				</li>
				<li class="col-md-3 wow">
					<div class="ico">
						<img src="{{ asset('img/' . Session::get('domain') . '/facts-ico2.webp') }}" alt="" width="40" height="">
					</div>
					<div class="wow fadeInUp" data-wow-delay="0" data-wow-duration="2s">
						<span>@lang('main-fly737.home.индивидуальный-подход')</span>
						<p>@lang('main-fly737.home.сотрудник-компании-быстро-реагирует')</p>
					</div>
				</li>
			</ul>
		</div>
	</div>

	<div class="variants" id="variants">
		<div class="container">
			<h2 class="block-title">@lang('main-fly737.home.варианты-полета')</h2>
		</div>
		<div class="items">
			<div class="text">
				<p>
					@lang('main-fly737.home.команда-может-предложить-любой-вариант-полёта')
					<a href="{{ url('flight-options') }}" class="button-pipaluk button-pipaluk-white"><i>@lang('main-fly737.home.подробнее2')</i></a>
				</p>
			</div>
			<div class="item-left" id="varsL">
				<img src="{{ asset('img/' . Session::get('domain') . '/img1.jpg') }}" alt="" width="100%" height="auto">
				<span>Land your airplane between the famous Austrian Alps ridges in the city of Innsbruck</span>
			</div>
			<div class="item-right" id="varsR">
				<div class="i-item">
					<img src="{{ asset('img/' . Session::get('domain') . '/mainimg.jpg') }}" alt="" width="100%" height="auto">
					<span>Enjoy picturesque views of Cote d'Azur in Cannes, Nice, and Monaco</span>
				</div>
				<div class="i-item">
					<img src="{{ asset('img/' . Session::get('domain') . '/img2.jpg') }}" alt="" width="100%" height="auto">
					<span>Make a circle around the tallest building in the world - Burj Khalifa in Dubai</span>
				</div>
				<div class="i-item">
					<img src="{{ asset('img/' . Session::get('domain') . '/img3.jpg') }}" alt="" width="100%" height="auto">
					<span>Choose any route and enjoy your flight</span>
				</div>
				<div class="i-item">
					<img src="{{ asset('img/' . Session::get('domain') . '/img4.webp') }}" alt="" width="100%" height="auto">
					<span>Fly over the majestic skyscrapers of New York at night</span>
				</div>
			</div>
		</div>
	</div>

	<div class="stock">
		<div class="container">
			<div class="row">
				<div class="col-md-8">
					<h2 class="block-title">@lang('main-fly737.home.акции')</h2>
					<div class="text">
						<ul style="color: #fff;">
							<li>
								<p>Weekdays happy hours from 12 PM to 2 PM get 15 minutes additional for free. Ready! Steady! Fly!</p>
							</li>
							<li>
								<p>Get a 20% discount if you visit us on your birthday, 3 days before or 3 days after your birthday. ID proof is required.</p>
							</li>
							<li>
								<p>Get 25% discount when purchasing 4 or more Gift Vouchers.</p>
							</li>
						</ul>
					</div>
				</div>
				<div class="col-md-4">
					<div class="img">
						<img src="{{ asset('img/' . Session::get('domain') . '/boeing-plane.png') }}" alt="" width="100%" height="auto">
					</div>
				</div>
			</div>
		</div>
	</div>

	@include(Session::get('domain') . '.forms.question')

	<p>&nbsp;&nbsp;&nbsp;*Not a 100% copy of a Boeing certified flight simulator</p>
@endsection

@push('css')
@endpush

@push('scripts')
	<script src="{{ asset('js/mainonly.js?' . time()) }}"></script>
@endpush
