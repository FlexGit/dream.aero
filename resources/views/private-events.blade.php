@extends('layouts.master')

@section('title')
	{{ $page->meta_title }}
@stop
@section('description', $page->meta_description)

@section('content')
	<div class="breadcrumbs container"><a href="{{ url(Request::get('cityAlias') ?? '/') }}">@lang('main.home.title')</a> <span>@lang('main.o-trenazhere.title')</span></div>

	<article class="article">
		<div class="container">
			<div class="relax corp">
				<div class="container">
					<div class="row">
						<h2 class="block-title">FLIGHTS FOR EVERY OCCASION</h2>
						<div id="birthday" class="col-md-8 wow fadeInLeft" data-wow-duration="2s" style="visibility: visible; animation-duration: 2s; animation-name: fadeInLeft;">
							<div class="text">
								<div class="wrapper1">
									<input type="radio" name="point1" id="slide3" checked="">
									<input type="radio" name="point1" id="slide4">
									<input type="radio" name="point1" id="slide5">
									<div class="slider1">
										<div class="slides slide3"></div>
										<div class="slides slide4"></div>
										<div class="slides slide5"></div>
									</div>
									<div class="controls1">
										<label for="slide3"></label>
										<label for="slide4"></label>
										<label for="slide5"></label>
									</div>
								</div>
							</div>
						</div>
						<div class="col-md-4 wow fadeInRight" data-wow-delay="1s" data-wow-duration="2s" style="visibility: visible; animation-duration: 2s; animation-delay: 1s; animation-name: fadeInRight;">
							<span>BIRTHDAY CELEBRATIONS</span>
							<p>Invite your friends and family on a trip across the skies.</p>
							<p>We can host any number of attendees of all ages and levels of experience.</p>
							<ul>
								<li>Small or large groups</li>
								<li>Kids, teens, adults, and seniors</li>
								<li>Frequent and first-time flyers are equally welcome</li>
							</ul>
							<p>Just fill us in on the details about your event and we’ll take care of the rest. We offer a wide range of options to customize your celebration.</p>
							<p>&nbsp;</p>
							<ul>
								<li>Party room with audio and video</li>
								<li>Catered food, cake, and beverages</li>
								<li>DJ or audio system with music</li>
								<li>Party requests and decorations</li>
							</ul>
							<a class="button-pipaluk button-pipaluk-orange" href="{{ url($cityAlias . '/private-events/#popup-call-back-new') }}"><i>REQUEST A CALL BACK</i></a>
						</div>
					</div>
					<div class="row" id="officeparties">
						<div class="col-md-4 wow fadeInLeft" data-wow-delay="1s" data-wow-duration="2s" style="visibility: visible; animation-duration: 2s; animation-delay: 1s; animation-name: fadeInLeft;">
							<span>OFFICE PARTIES</span>
							<div class="text">
								<p>Crosscheck your work-life balance with something thrilling and educational.&nbsp;</p>
								<p>Take turns at the flight controls or enjoy the immersive views from the jumpseat and external monitors. Our flight deck is the perfect place for friendly competition and collaboration.</p>
							</div>
							<ul>
								<li>Use our flight simulator for a variety of educational and team-building exercises.</li>
								<li>Enjoy our custom-made programs for groups of 3 to 20 people.</li>
								<li>Catering services, MC, and custom branding options must be arranged in advance.</li>
								<li>Prized competitions with prizes may also be arranged.</li>
							</ul>
							<a class="button-pipaluk button-pipaluk-orange" href="{{ url($cityAlias . '/private-events/#popup-call-back-new') }}"><i>REQUEST A CALL BACK</i></a>
						</div>
						<div class="col-md-8 wow fadeInRight" style="padding-left: 20px; visibility: visible; animation-duration: 2s; animation-name: fadeInRight;" data-wow-duration="2s">
							<div class="wrapper">
								<input type="radio" name="point" id="slide1" checked="">
								<input type="radio" name="point" id="slide2">
								<div class="slider">
									<div class="slides slide1"></div>
									<div class="slides slide2"></div>
								</div>
								<div class="controls">
									<label for="slide1"></label>
									<label for="slide2"></label>
								</div>
							</div>
						</div>
					</div>
					<div class="row" id="socials">
						<div class="col-md-8 wow fadeInLeft" data-wow-duration="2s" style="visibility: visible; animation-duration: 2s; animation-name: fadeInLeft;">
							<div class="text">
								<p><img src="{{ asset('img/7V1B0717.jpg') }}" width="90%" alt=""></p>
							</div>
						</div>
						<div class="col-md-4 wow fadeInRight" data-wow-delay="1s" data-wow-duration="2s" style="visibility: visible; animation-duration: 2s; animation-delay: 1s; animation-name: fadeInRight;">
							<p class="span">SOCIALS</p>
							<p>Plan something different.</p>
							<p>Dream Aero is the perfect indoor venue for any kind of social gathering. From fundraisers to club meetups, graduations to youth groups. You name it. We prepare the cabin.</p>
							<a class="button-pipaluk button-pipaluk-orange" href="{{ url($cityAlias . '/private-events/#popup-call-back-new') }}"><i>REQUEST A CALL BACK</i></a>
						</div>
					</div>

					<div class="row">
						<form method="post" id="popup-call-back-new" class="popup popup-call-back ajax_form">
							<h2>REQUEST A CALL BACK</h2>
							<span>Fill out just a couple of fields and we will contact you in the nearest future</span>
							<input type="text" id="name" name="name" placeholder="Your name" class="popup-input" required>
							<input type="tel" id="phone" name="phone" placeholder="What is your phone number" class="popup-input" required>
							<textarea id="comment" name="comment" placeholder="Comment" class="popup-area"></textarea>
							<span class="nice-select-label city">City: <b>{{ $city->name }}</b></span>
							<div class="consent-container">
								<label class="cont">
									I hereby give my consent to process my personal data.
									<input type="checkbox" name="consent" value="1">
									<span class="checkmark" style="padding-bottom: 0;"></span>
								</label>
								<a href="{{ url(($cityAlias ?? '') . '/privacy-policy') }}" target="_blank">Learn more</a>
							</div>
							<div style="margin-top: 10px;">
								<div class="alert alert-success hidden" role="alert">
									@lang('main.modal-callback.запрос-успешно-отправлен')
								</div>
								<div class="alert alert-danger hidden" role="alert"></div>
							</div>
							<button type="button" onclick="fbq('track', 'Purchase');" class="popup-submit button-pipaluk button-pipaluk-grey js-callback-btn" disabled><i>Submit</i></button>
						</form>
					</div>
				</div>
			</div>
		</div>
	</article>
@endsection

@push('css')
	<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
	<link rel="stylesheet" href="{{ asset('css/jquery.datetimepicker.min.css') }}">
	<style>
		.relax h2.block-title {
			margin: 90px 0 70px;
		}

		.relax .col-md-4.wow.fadeInRight span,.corp span,.corp .span {
			font-size: 22px;
			font-weight: bold;
			margin-bottom: 45px;
			display: block;
		}
		.relax .col-md-4 li {
			padding-bottom: 20px;
		}
		.relax .col-md-4 p{
			width:90%;
		}
		.relax .col-md-4 p {
			font-size: 16px;

		}
		.relax i{
			color:white;
		}
		.relax .col-md-4.wow.fadeInRight a,.corp a {
			font-size: 16px;
			color: #FF8200;
			text-decoration: none;

		}
		.corp a {
			display: block;
			width: 70%;
			text-align: center;
			margin: 0 auto;
		}
		.relax {
			overflow: hidden;
		}
		.relax .col-md-4{
			padding: 100px 0px 100px 41px;
			margin: 50px 0px 0px;
			background: #f7f7f9;
		}

		.relax ul {
			padding: 0 15px 0px;
		}
		.relax .col-md-4:after, .relax .kids:after {
			content: '';
			width: 100%;
			height: 100%;
			position: absolute;

			top: 0;
			background: #f7f7f9;

		}
		.relax .col-md-4.fadeInLeft:after{
			left: -100%;
		}
		.relax .col-md-4.fadeInRight:after{
			right: -100%;
		}
		.stock.under {
			margin-top: 0;
		}
		.relax .col-md-8 .text p {
			margin-bottom: 30px;
		}
		.relax .col-md-8 .text {
			padding: 0 30px 0 0;
		}
		.relax .col-md-8 {
			padding: 0;
		}
		.corp,.corp .row{
			margin-bottom:50px;
		}
		.relax .col-md-8 .text a, .corp a {
			padding: 20px;
			text-transform: uppercase;
			top: 25px;
		}

		.wrapper,.wrapper1 {
			height: 550px;
			margin: 0px auto;
			position: relative;
			width: 100%;
		}
		@media screen and (max-width: 692px){
			.wrapper1{
				height:350px;
			}
		}
		@media screen and (max-width: 500px){
			.wrapper1{
				height:250px;
			}
		}
		.slider,.slider1 {

			height: inherit;
			overflow: hidden;
			position: relative;
			width: inherit;

		}
		.wrapper > input,.wrapper1 > input {
			display: none;
		}
		.slides,.slides1 {
			height: inherit;
			position: absolute;
			width: inherit;
			background-size: contain;
			background-repeat: no-repeat;
			background-position: center;
		}

		.slide1 { background-image: url({{ asset('img/NIK_4441.jpg') }}); }
		.slide2 { background-image: url({{ asset('img/2.jpg') }}); }
		.slide3 { background-image: url({{ asset('img/adultparty.jpg') }}); }
		.slide4 { background-image: url({{ asset('img/kidsparty.jpg') }}); }
		.slide5 { background-image: url({{ asset('img/kid.jpg') }}); }
		.wrapper .controls,.wrapper1 .controls1 {
			text-align: center;
		}

		.wrapper label,.wrapper1 label {
			cursor: pointer;
			display: inline-block;
			height: 8px;
			margin: 25px 12px 0 16px;
			position: relative;
			width: 8px;
			-webkit-border-radius: 50%;
			-moz-border-radius: 50%;
			-o-border-radius: 50%;
			border-radius: 50%;
		}

		.wrapper label:after,.wrapper1 label:after {
			border: 2px solid #FF8200;
			content: " ";
			display: block;
			height: 12px;
			left: -1.7px;
			position: absolute;
			top: -2.3px;
			width: 12px;
			-webkit-border-radius: 50%;
			-moz-border-radius: 50%;
			-o-border-radius: 50%;
			border-radius: 50%;
		}
		.wrapper label,.wrapper1 label {
			cursor: pointer;
			display: inline-block;
			height: 8px;
			margin: 25px 12px 0 16px;
			position: relative;
			width: 8px;
			-webkit-border-radius: 50%;
			-moz-border-radius: 50%;
			-o-border-radius: 50%;
			border-radius: 50%;
			-webkit-transition: background ease-in-out .5s;
			-moz-transition: background ease-in-out .5s;
			-o-transition: background ease-in-out .5s;
			transition: background ease-in-out .5s;
		}

		#slide3:checked ~ .controls1 label:nth-of-type(1),
		#slide4:checked ~ .controls1 label:nth-of-type(2),
		#slide5:checked ~ .controls1 label:nth-of-type(3){
			background: #FF8200;
		}
		.wrapper label:hover, .wrapper1 label:hover,
		#slide1:checked ~ .controls label:nth-of-type(1),
		#slide2:checked ~ .controls label:nth-of-type(2)
		{
			background: #FF8200;
		}
		.slides,.slides1 {
			height: inherit;
			opacity: 0;
			position: absolute;
			width: inherit;
			z-index: 0;
			-webkit-transform: scale(1.5);
			-moz-transform: scale(1.5);
			-o-transform: scale(1.5);
			transform: scale(1.5);
			-webkit-transition: transform ease-in-out .5s, opacity ease-in-out .5s;
			-moz-transition: transform ease-in-out .5s, opacity ease-in-out .5s;
			-o-transition: transform ease-in-out .5s, opacity ease-in-out .5s;
			transition: transform ease-in-out .5s, opacity ease-in-out .5s;
		}
		#slide3:checked ~ .slider1 > .slide3,
		#slide4:checked ~ .slider1 > .slide4,
		#slide5:checked ~ .slider1 > .slide5{
			opacity: 1;
			z-index: 1;
			-webkit-transform: scale(1);
			-moz-transform: scale(1);
			-o-transform: scale(1);
			transform: scale(1);
		}
		#slide1:checked ~ .slider > .slide1,
		#slide2:checked ~ .slider > .slide2{
			opacity: 1;
			z-index: 1;
			-webkit-transform: scale(1);
			-moz-transform: scale(1);
			-o-transform: scale(1);
			transform: scale(1);
		}
		form#popup-call-back-new .block {
			width: 400px;
			margin: auto;
		}
		input[type="radio"], input[type="checkbox"] {
			margin: 4px 0 0;
			margin-top: 1px;
			line-height: normal;
		}
		form#popup-call-back-new .block span {
			padding-bottom: 0;
		}
		.popup .nice-select-label {
			display: inline;
			font-family: "GothamProMedium";
			font-size: 13px;
			color: #4d4d51;
		}
		.relax .col-md-4.wow.fadeInRight span, .corp span, .corp .span {
			font-size: 22px;
			font-weight: bold;
			margin-bottom: 45px;
			display: block;
		}
	</style>
@endpush

@push('scripts')
	<script src="{{ asset('js/jquery.datetimepicker.full.min.js') }}"></script>
	<script src="{{ asset('js/deal.js?v=' . time()) }}"></script>
@endpush