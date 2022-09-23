@extends('layouts.master')

@section('title')
	{{ $page->meta_title }}
@stop
@section('description', $page->meta_description)

@section('content')
	<div class="breadcrumbs container"><a href="{{ url('/') }}">@lang('main.home.title')</a> <span>@lang('main.o-trenazhere.title')</span></div>

	<article class="article">
		<div class="container">
			<div class="relax corp">
				<div class="container">
					<h1 class="article-title">FLIGHT BRIEFING</h1>
					<div class="article-content">
						<div class="row">
							<div class="col-md-12">
								<div class="item">
									<p>For your convenience and to save time, learn the basics of the Boeing 737 NG and complete your briefing before your flight.</p>
									<p>&nbsp;</p>
									<div id="youtuber"><iframe class="youvideo" src="https://www.youtube.com/embed/IDBDqMvlvcI?rel=0&amp;autoplay=1&amp;mute=1" width="300" height="150" frameborder="0" allowfullscreen="allowfullscreen"></iframe></div>
									<div class="block-break" style="height: 30px; clear: both;">&nbsp;</div>
								</div>
							</div>
						</div>
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
	</style>
@endpush

@push('scripts')
	<script src="{{ asset('js/jquery.datetimepicker.full.min.js') }}"></script>
	<script src="{{ asset('js/deal.js?v=' . time()) }}"></script>
@endpush