@extends('layouts.master')

@section('title')
	{{ $page->meta_title }}
@stop
@section('description', $page->meta_description)

@section('content')
	<div class="breadcrumbs container"><a href="{{ url($cityAlias ?? '/') }}">@lang('main.home.title')</a> <span>AN UNFORGETTABLE EXPERIENCE AND A LASTING IMPRESSION</span></div>

	<div class="about simul" id="about">
		<div class="container">
			<h2 class="block-title">AN UNFORGETTABLE EXPERIENCE AND A LASTING IMPRESSION</h2>
			<div class="text-block wow fadeInRight simul" data-wow-delay="0.5s" data-wow-duration="2s" style="visibility: visible; animation-duration: 2s; animation-delay: 0.5s; animation-name: fadeInRight;">
				<p>Want to become the pilot of a real passenger airplane? You might think that this is impossible or that your chance has long passed. Think again! Anyone can pilot a plane with the Dream Aero Flight Simulator. Make your dream a reality.</p>
				<p>The simulator provides a 100% authentic impression of a real flight. From the moment you enter the cockpit of the flight simulator, you will become immersed in the wonderful world of aviation.</p>
			</div>
		</div>
		<div class="image wow fadeInLeft" data-wow-delay="1s" data-wow-duration="2s" style="visibility: visible; animation-duration: 2s; animation-delay: 1s; animation-name: fadeInLeft;">
			<iframe width="100%" src="{{ asset('img/visual.jpg') }}" frameborder="0" scrolling="no" allowfullscreen=""></iframe>
		</div>
		<div class="container">
			<br>
			<br>
			<br>
			<br>
			<div class="about-simulator">
				<h2>Now I’m a pilot!</h2>
				<p>The virtual reality of our simulation is an experience so life-like that you can’t help but imagine yourself as a pilot. Your instructor, in the first officer’s seat, will help you get your plane fully under control and avoid any potential mishaps.</p>
				<p>All of the equipment in the cockpit simulator, including control panels, levers, and the steering wheel are the same as found on board a real aircraft.</p>
				<ul>
					<li>The mobile platform is connected to a computer that responds to every maneuver the pilot makes. As you accelerate down the runway you will feel the bumps between the slabs of concrete. Your entire body will feel the moment the plane lifts off the ground. You will react to acceleration, air pockets, turns and maneuvers, as well as the descent of the airplane.</li>
					<li>The cockpit glass is built with a hi-tech panoramic video system. Real terrain images are displayed on panoramic screens. You can choose almost any airport in the world for your takeoff or landing. The vivid display is complimented by all sounds that can be heard in the aircraft during takeoff, landing or while in-flight.</li>
					<li>You may take two friends inside the cockpit as passengers during your flight simulation. Our visitors agree that flying as a passenger is no less exciting than piloting the plane, especially as the pilot is usually busy at the controls. Your friends will experience all the nuances of plane dynamics, visualization and in-flight sound.</li>
				</ul>
				<p>It is difficult to describe the emotions you experience when you take control of a real aircraft. What a thrill it is to feel the steel bird follow your every command! Remember back to the first time you drove a car - multiply those emotions by ten, or, better yet, 100.</p>
				<p>Flying over clouds is a dream that many people cherish. Turn your childhood dreams into reality with our flight simulator. A gift certificate is an excellent way to share a lasting experience with your friends and loved ones. You will see how easy it is to make dreams come true. Book a flight session with Dream Aero and experience the thrill of flying.</p>
			</div>
		</div>
		<p></p>
		<p></p>
	</div>
@endsection

@push('css')
	<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
	<link rel="stylesheet" href="{{ asset('css/jquery.datetimepicker.min.css') }}">
	{{--<style>
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

		.slide1 { background-image: url({{ asset('img/NIK_4441.JPG') }}); }
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
	</style>--}}
@endpush

@push('scripts')
	<script src="{{ asset('js/jquery.datetimepicker.full.min.js') }}"></script>
	<script src="{{ asset('js/deal.js?v=' . time()) }}"></script>
@endpush