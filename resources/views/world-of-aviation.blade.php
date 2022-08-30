@extends('layouts.master')

@section('title')
	{{ $page->meta_title }}
@stop
@section('description', $page->meta_description)

@section('content')
	<div class="breadcrumbs container"><a href="{{ url($cityAlias ?? '/') }}">@lang('main.home.title')</a> <span>@lang('main.o-trenazhere.title')</span></div>

	<div class="about simul" id="about">
		<div class="container">
			<h2 class="block-title">Immersion in the World of Aviation</h2>
			<div class="text-block wow fadeInRight simul" data-wow-delay="0.5s" data-wow-duration="2s" style="visibility: visible; animation-duration: 2s; animation-delay: 0.5s; animation-name: fadeInRight;">
				<p>Are you open to new experiences? If so, then why not try piloting a passenger airliner? Dream Aero offers you a virtual trip in the cockpit of our flight simulator.</p>
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
				<h2>Accurate and true-to-life visualization</h2>
				<p>Visual effects and tactile impressions combine to provide a very authentic flight experience. Behind the panoramic glass of the cockpit is a state-of-the-art video display system.</p>
				<ul>
					<li>Sitting in the captain’s seat, the pilot sees what a pilot would see during a real flight.</li>
					<li>The image on the screen will change as the aircraft changes its position.</li>
					<li>The system includes a complete geographic model of the globe, with all rivers, lakes, seas, and mountain ranges held in the computer’s memory. The approach zones of all major airports are accurately represented in our system.</li>
					<li>Choose& any place you wish to visit. You can choose locations you’ve never been to, or places where you’ll recognize every building or bush. Choose any destination in the world!</li>
				</ul>
				<p>Flying above mountains or flying over seas can differ greatly, both in optics and in how you pilot the aircraft. In the first officer’s seat, your instructor will help you navigate the plane safely above mountain tops. When flying over seas, you will need to fly at a certain altitude and be prepared for frequently changing air flows.</p>
				<p>A half hour or even a full hour of training is not enough to become a real pilot, but you will certainly learn to appreciate the complexities and nature of a pilot’s work. Piloting the plane will help many people deal with aerophobia and any travel-related anxiety. After your simulation experience, you will at least get a general idea of what a pilot does when the plane takes off, when you experience turbulence, sharp turns, and when landing the plane.</p>
				<p>Share the joy of this new experience with your family and friends. Take two friends along in the passenger seats behind you, so they can enjoy all the visual and sound effects of your flight. They may by all means take a turn at the wheel themselves and learn to fly the aircraft!</p>
				<p>Book a session on our simulator and enjoy the romantic spirit of flying from the captain’s perspective.</p>
				<h2>True-to-life, without limitations</h2>
				<p>Our flight simulators fly in all weather conditions. Thunderstorms, hurricanes, thick fog or heavy snow will not be an obstacle. There are no delays when flying with Dream Aero - our airline arrives at any location, precisely on time.</p>
				<p>&nbsp;</p>
			</div>
		</div>
	</div>
@endsection

@push('css')
	<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
	<link rel="stylesheet" href="{{ asset('css/jquery.datetimepicker.min.css') }}">
@endpush

@push('scripts')
	<script src="{{ asset('js/jquery.datetimepicker.full.min.js') }}"></script>
	<script src="{{ asset('js/deal.js?v=' . time()) }}"></script>
@endpush