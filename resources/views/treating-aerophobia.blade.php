@extends('layouts.master')

@section('title')
	{{ $page->meta_title }}
@stop
@section('description', $page->meta_description)

@section('content')
	<div class="breadcrumbs container"><a href="{{ url($cityAlias ?? '/') }}">@lang('main.home.title')</a> <span>TREATING AEROPHOBIA</span></div>

	<div class="about simul" id="about">
		<div class="container">
			<h2 class="block-title">Treating Aerophobia</h2>
			<div class="text-block wow fadeInRight simul" data-wow-delay="0.5s" data-wow-duration="2s" style="visibility: visible; animation-duration: 2s; animation-delay: 0.5s; animation-name: fadeInRight;">
				<p>Many people are afraid of flying. This is a normal reaction when placed in an uncomfortable environment. Most people can easily keep these fears under control, but when fear takes hold, you experience what is known as a phobia. Phobias have a profoundly negative effect on our lives, and they must be addressed as serious concerns. Phobias are often detached from objective reality, and in many instances are irrational. Someone with aerophobia may have never flown before, but nevertheless, that person may be fearful. Comparably, they may be aware that riding in a car is much more dangerous, yet, they still may be unable to overcome their phobia.</p>
			</div>
		</div>
		<div class="image wow fadeInLeft" data-wow-delay="1s" data-wow-duration="2s" style="visibility: visible; animation-duration: 2s; animation-delay: 1s; animation-name: fadeInLeft;">
			<iframe width="100%" src="{{ asset('img/aerofobia.jpg') }}" frameborder="0" scrolling="no" allowfullscreen=""></iframe>
		</div>
		<div class="container">
			<br>
			<br>
			<br>
			<br>
			<div class="about-simulator">
				<p>According to statistical data, some 20% of all people choose not to fly because of aerophobia.</p>
				<p>Aerophobia imposes considerable limitations on a person’s ability to travel. This can have a large effect, not only in their personal life, but in their professional life as well.</p>
				<p>People who suffer from aerophobia may have issues with separate stages of the flight, such as takeoff or landing, moments of turbulence as well as the very thought of being high up in the sky. For some, it may simply be the lack of control they have over the situation.</p>
				<p><strong>Flight simulators as means of treating aerophobia</strong></p>
				<p>Dream Aero is happy to offer a helping hand to those willing to take the next step toward treating their aerophobia.</p>
				<p>A flight simulator creates a virtual reality that fully imitates an authentic flight environment. Controlled exposure to this artificial environment helps people overcome their fears. By controlling the flight simulator, people with aerophobia come closer to controlling the situation, thus making it easier for them to overcome a panic attack or avoid one altogether. The same applies, for instance, to driving a car. The driver feels more calm and confident at the wheel as opposed to being in the back seat.</p>
				<p><strong>Taking it step by step</strong></p>
				<p>In our flight simulators, the process of controlling the aircraft must be recreated in minute detail. The person operating the simulator will become familiar with a number of standard operations.</p>
				<ul>
					<li>Take off.</li>
					<li>Maintaining course.</li>
					<li>Landing.</li>
				</ul>
				<p>It is much harder, naturally, to control the aircraft in adverse weather conditions or during an emergency. This is nothing to be concerned about as we are not aiming to train people to become pilots. Our main goal is to help people get rid of aerophobia, control or avoid panic attacks, and to stop viewing an ordinary flight as a terrible ordeal. With the assistance of our flight simulators, our visitors may be better equipped at managing their everyday lives.</p>
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