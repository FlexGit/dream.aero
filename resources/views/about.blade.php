@extends('layouts.master')

@section('title')
	{{ $page->meta_title }}
@stop
@section('description', $page->meta_description)

@section('content')
	<div class="breadcrumbs container"><a href="{{ url(Request::get('cityAlias') ?? '/') }}">@lang('main.home.title')</a> <span>@lang('main.o-trenazhere.title')</span></div>

	<div class="about simul" id="about">
		<div class="container">
			<h2 class="block-title">@lang('main.o-trenazhere.title')</h2>
			<div class="gallery-button-top">
				<div class="button-free">
					<a href="{{ url('#popup') }}" class="obtain-button button-pipaluk button-pipaluk-orange wow zoomIn popup-with-form form_open" data-modal="booking" style="padding: 10px;margin: 0 0 35px 36%;width: 280px;" data-wow-delay="1.6s" data-wow-duration="2s" data-wow-iteration="1">
						<i>Buy Now</i>
					</a>
				</div>
			</div>
			<div class="text-block wow fadeInRight simul" data-wow-delay="0.5s" data-wow-duration="2s" style="visibility: visible;animation-duration: 2s;animation-delay: 0.5s;animation-name: fadeInRight;margin-top: 0;">
				<p>@lang('main.o-trenazhere.компания-предлагает-вам-отправиться-в-полет')</p>
			</div>
		</div>
		<div class="image wow fadeInLeft" data-wow-delay="1s" data-wow-duration="2s" style="visibility: visible;animation-duration: 2s;animation-delay: 1s;animation-name: fadeInLeft;">
			<iframe width="100%" src="{{ asset('img/aboutsim2.jpg') }}" frameborder="0" scrolling="no" allowfullscreen></iframe>
		</div>
	</div>

	<article class="article">
		<div class="container">
			<div class="article-content">
				<div class="row">
					<div class="col-md-12 about-simulator">
						@lang('main.o-trenazhere.авиасимулятор-в-точности-воспроизводит-нюансы-управления')
						<div id="tvyouframe" style="margin-top: 20px;">
							<div id="youtuber">
								<iframe src="https://www.youtube.com/embed/QXT6er6Pzuk?autoplay=1&mute=1" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen class="youvideo"></iframe>
							</div>
						</div>

						<h2>@lang('main.o-trenazhere.что-мы-предлагаем')</h2>

						<a href="{{ url('#popup-offer-1') }}" class="popup-with-form offer" style="background-image: url({{ asset('img/blok_1.png') }});background-position: top; background-size: cover;" data-popup-type="info" data-alias="offer-1">
							<img src="{{ asset('img/facts-ico3.png') }}" alt="">
							<p class="bold">SUPPORT OF AN EXPERIENCED PILOT INSTRUCTOR</p>
							<p></p>
						</a>

						<a href="{{ url('#popup-offer-2') }}" class="popup-with-form offer" style="background-image: url({{ asset('img/Blok_2.png') }});background-position: top; background-size: cover;" data-popup-type="info" data-alias="offer-2">
							<img src="{{ asset('img/facts-ico1.png') }}" alt="">
							<p class="bold">IMMERSION IN THE WORLD OF AVIATION</p>
							<p></p>
						</a>

						<a href="{{ url('#popup-offer-3') }}" class="popup-with-form offer" style="background-image: url({{ asset('img/Blok_3.png') }});background-position: top; background-size: cover;" data-popup-type="info" data-alias="offer-3">
							<img src="{{ asset('img/facts-ico2.png') }}" alt="">
							<p class="bold">ASSISTANCE WITH AEROPHOBIA AND TRAVEL-RELATED ANXIETY </p>
							<p></p>
						</a>

						<a href="{{ url('#popup-offer-4') }}" class="popup-with-form offer" style="background-image: url({{ asset('img/Blok_4.png') }});background-position: top; background-size: cover;" data-popup-type="info" data-alias="offer-4">
							<img src="{{ asset('img/facts-ico4.png') }}" alt="">
							<p class="bold">A SENSATIONAL AND LASTING EXPERIENCE, BRINGING YOU AS CLOSE AS POSSIBLE TO PILOTING YOUR OWN BOEING 737 NG PASSENGER AIRLINER </p>
							<p></p>
						</a>

						<h2>THE BOEING 737 NG FAMILY</h2>

						<p><img src="{{ asset('img/B737_NG.jpg') }}" alt="" width="100%" /></p>

						<blockquote>
							<p>@lang('main.o-trenazhere.boeing-737-самый-популярный')</p>
						</blockquote>
						<p>@lang('main.o-trenazhere.boeing-737-ng-считаются-самыми-популярными')</p>
						<h2 class="western">@lang('main.o-trenazhere.три-поколения-boeing-737')</h2>
						<ul>
							<li>@lang('main.o-trenazhere.original')</li>
							<li>@lang('main.o-trenazhere.classic')</li>
							<li>@lang('main.o-trenazhere.next-generation')</li>
						</ul>
						@lang('main.o-trenazhere.начиная-с-1984-года')
						<h3>@lang('main.o-trenazhere.технические-данные')</h3>
						<div class="table">
							<div class="tr">
								<p>@lang('main.o-trenazhere.максимум-взлётной-массы')</p>
								<p>66 — 83,13 @lang('main.o-trenazhere.tons')</p>
							</div><div class="tr">
								<p>@lang('main.o-trenazhere.наибольшая-дальность')</p>
								<p>5,648 — 5,925 @lang('main.o-trenazhere.km')</p>
							</div><div class="tr">
								<p>@lang('main.o-trenazhere.крейсерская-скорость')</p>
								<p>0.785 @lang('main.o-trenazhere.M')</p>
							</div><div class="tr">
								<p>@lang('main.o-trenazhere.размах-крыла')</p>
								<p>34.3 @lang('main.o-trenazhere.m')</p>
							</div><div class="tr">
								<p>@lang('main.o-trenazhere.с-законцовками')</p>
								<p>35.8 @lang('main.o-trenazhere.m')</p>
							</div><div class="tr">
								<p>@lang('main.o-trenazhere.длина-аппарата')</p>
								<p>31.2 — 42.1 @lang('main.o-trenazhere.m')</p>
							</div><div class="tr">
								<p>@lang('main.o-trenazhere.высота-по-хвостовому-оперению')</p>
								<p>12.6 @lang('main.o-trenazhere.m')</p>
							</div><div class="tr">
								<p>@lang('main.o-trenazhere.ширина-пассажирской-кабины')</p>
								<p>3.53 @lang('main.o-trenazhere.m')</p>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</article>

	@include('forms.question')
@endsection

@push('css')
	<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
	<link rel="stylesheet" href="{{ asset('css/jquery.datetimepicker.min.css') }}">
	<style>
		.about-simulator p,
		.about-simulator ul li {
			color: #515050;
			font-size: 19px;
			margin: 0 0 25px;
		}
		.about-simulator h2 {
			font-weight: 600;
			padding: 90px 0 60px;
		}
		.about-simulator .bold {
			font-weight: 600;
			margin-top: 35px;
			color: black;
		}
		.about-simulator h3 {
			text-align: center;
			margin-top: 100px;
			margin-bottom: 0;
			background: #f04915;
			color: white;
			padding: 20px;
			text-transform: uppercase;
			font-size: 20px;
		}
		#airboeing {
			width: 98%;
		}
		#airboeing .simtype {
			color: #FF8200;
			font-weight: 700;
			font-size: 16px;
		}
		#airboeing .tdcity {
			color: #FF8200;
		}
		#airboeing td {
			width: 50%;
			text-align: left;
		}
		td.halfnhalf {
			background: linear-gradient(to bottom, #f0f0f0 0%, #f0f0f0 50%, white 50%, white 100%);
		}
		td.halfnhalf_white {
			background: linear-gradient(to bottom, white 0%, white 50%, #f0f0f0 50%, #f0f0f0 100%);
		}
	</style>
@endpush

@push('scripts')
	<script src="{{ asset('js/jquery.datetimepicker.full.min.js') }}"></script>
	<script src="{{ asset('js/deal.js?v=' . time()) }}"></script>
	<script>
		$(function() {
		});
	</script>
@endpush