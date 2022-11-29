@extends('layouts.master')

@section('title')
	{{ $page->meta_title }}
@stop
@section('description', $page->meta_description)

@section('content')
	<div class="breadcrumbs container"><a href="{{ url($cityAlias ?? '/') }}">@lang('main.home.title')</a> <span>Gift vouchers</span></div>

	<div class="about simul" id="about">
		<div class="container">
			<h2 class="block-title">GIFT VOUCHERS</h2>

			<div class="text-block wow fadeInRight simul" data-wow-delay="0.5s" data-wow-duration="2s" style="visibility: visible; animation-duration: 2s; animation-delay: 0.5s; animation-name: fadeInRight; margin-top: 0;">
				<p><a href="{{ url('#popup') }}" class="button-pipaluk button-pipaluk-white popup-with-form form_open" data-modal="certificate"><span style="color: #f35d1c;">Buy Now</span></a></p>
				<p>@lang('main.gift-certificates.кто-не-мечтал-в-детстве-стать-лётчиком')</p>
			</div>
		</div>
		<div class="image wow fadeInLeft" data-wow-delay="1s" data-wow-duration="2s" style="visibility: visible; animation-duration: 2s; animation-delay: 1s; animation-name: fadeInLeft;">
			<iframe width="100%" src="{{ asset('img/aerofobia.jpg') }}" frameborder="0" scrolling="no" allowfullscreen></iframe>
		</div>
	</div>

	<article class="article">
		<div class="container">
			<div class="article-content">
				<div class="row">
					<div class="col-md-12 about-simulator">
						<h2>@lang('main.gift-certificates.что-мы-предлагаем')</h2>
						<a href="{{ url('#popup-offer-1') }}" class="popup-with-form offer" style="background-image: url({{ asset('img/blok_1.png') }});background-position: top; background-size: cover;" data-popup-type="info" data-alias="offer-5">
							<img src="{{ asset('img/facts-ico3.png') }}" alt="">
							<p class="bold">100% AUTHENTIC COCKPIT</p>
							<p>Feel the flight controls reverberate in your hands and the might of the aircraft as it pierces through the air. Learn the complexities of flying a plane aboard a simulator that provides a very lifelike experience. Our cockpit simulator is an exact copy of a real cockpit, and all equipment found on board is identical to the instruments found on a real aircraft.</p>
						</a>
						<a href="{{ url('#popup-offer-2') }}" class="popup-with-form offer" style="background-image: url({{ asset('img/Blok_2.png') }});background-position: top; background-size: cover;" data-popup-type="info" data-alias="offer-6">
							<img src="{{ asset('img/facts-ico1.png') }}" alt="">
							<p class="bold">DYNAMIC PLATFORM</p>
							<p>The platform moves with every motion throughout your journey. It will allow you to feel the bumps of the runway as you accelerate, feel the slightest turn of the aircraft while flying, as well as the intensity of the descent to your destination. The high-output computer hydraulic system will provide the most realistic illusion of a flight.</p>
						</a>
						<a href="{{ url('#popup-offer-3') }}" class="popup-with-form offer" style="background-image: url({{ asset('img/Blok_3.png') }});background-position: top; background-size: cover;" data-popup-type="info" data-alias="offer-7">
							<img src="{{ asset('img/facts-ico2.png') }}" alt="">
							<p class="bold">PILOTING ASSISTANCE</p>
							<p>Our pilot instructor will provide you with all the support you need to operate the flight simulator and feel safe at all times. Furthermore, the instructor will help you better understand the controls of a modern aircraft. Our team includes retired and active duty pilots who know the ins and outs of their profession.</p>
						</a>
						<a href="{{ url('#popup-offer-4') }}" class="popup-with-form offer" style="background-image: url({{ asset('img/Blok_4.png') }});background-position: top; background-size: cover;" data-popup-type="info" data-alias="offer-8">
							<img src="{{ asset('img/facts-ico4.png') }}" alt="">
							<p class="bold">TRUE TO LIFE VISUALIZATION</p>
							<p>Our incredibly accurate and detailed visualization system imitates a real flight environment and aircraft operations.</p>
						</a>
					</div>
				</div>
			</div>
		</div>
	</article>

	@include(Session::get('domain') . '.forms.question')
@endsection

@push('css')
	<link rel="stylesheet" href="{{ asset('css/simulstyle.css') }}">
	<link rel="stylesheet" href="{{ asset('css/jquery.datetimepicker.min.css') }}">
	<link rel="stylesheet" href="{{ asset('css/card.css') }}">
	<link rel="stylesheet" href="{{ asset('css/scale.css') }}">
	<link rel="stylesheet" href="{{ asset('css/light-border.css') }}">
	<style>
		.offer:nth-of-type(2n+2) {
			margin-right: 7px;
		}
		@media screen and (max-width: 767px) {
			.offer {
				width: 100% !important;
				margin: 20px 0;
			}
		}
		@media screen and (max-width: 991px) {
			.offer {
				width: 49%;
			}
		}
		@media screen and (max-width: 1199px) {
			.offer {
				width: 49%;
			}
		}
		@media screen and (max-width: 1280px) {
			.offer {
				width: 49%;
			}
		}
		@media screen and (max-width: 1500px) {
			.offer {
				width: 49%;
			}
			.offer {
				width: 49%;
				margin: 1.36% 1% 0 0;
				display: inline-block;
				padding: 35px;
				min-height: 320px;
				vertical-align: top;
				background: url({{ asset('img/dots.png') }}) no-repeat 60px 35px;
			}
		}
		.about-simulator .offer {
			background-position: top;
			background-size: cover;
			padding-bottom: 10px;
		}
		.about-simulator .offer p:last-of-type {
			font-size: 15px;
		}
		.about-simulator p:last-of-type {
			margin: 0;
		}
		.about-simulator p, .about-simulator ul li {
			color: #515050;
			font-size: 15px;
		}
	</style>
@endpush

@push('scripts')
	<script src="{{ asset('js/jquery.datetimepicker.full.min.js') }}"></script>
	<script src="{{ asset('js/jquery.card.js') }}"></script>
	<script src="{{ asset('js/popper.min.js') }}"></script>
	<script src="{{ asset('js/tippy-bundle.umd.min.js') }}"></script>
	<script src="{{ asset('js/deal.js?v=10') }}"></script>
@endpush