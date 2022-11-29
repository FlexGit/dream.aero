@extends('layouts.master')

@section('title')
	{{ $page->meta_title }}
@stop
@section('description', $page->meta_description)

@section('content')
	<div class="breadcrumbs container"><a href="{{ url($cityAlias ?? '/') }}">@lang('main.home.title')</a> <span>PROFESSIONAL ASSISTANCE</span></div>

	<div class="about simul" id="about">
		<div class="container">
			<h2 class="block-title">Professional assistance</h2>
			<div class="text-block wow fadeInRight simul" data-wow-delay="0.5s" data-wow-duration="2s" style="visibility: visible; animation-duration: 2s; animation-delay: 0.5s; animation-name: fadeInRight;">
				<p>Enjoy a safe and fascinating flight aboard our flight simulator while accompanied by one of our experienced instructors. Our instructors have extensive piloting experience and know all the nuances of safely flying a large aircraft.</p>
			</div>
		</div>
		<div class="image wow fadeInLeft" data-wow-delay="1s" data-wow-duration="2s" style="visibility: visible; animation-duration: 2s; animation-delay: 1s; animation-name: fadeInLeft;">
			<iframe width="100%" src="{{ asset('img/prof-help2-min.jpg') }}" frameborder="0" scrolling="no" allowfullscreen=""></iframe>
		</div>
		<div class="container">
			<br>
			<br>
			<br>
			<br>
			<div class="about-simulator">
				<h2>Flight Simulator / Cockpit Environment</h2>
				<p>The cockpit simulator is equipped just like the cockpit of a real aircraft. The pilot seats, control panels, displays, panoramic glass and steering wheel create the impression of a real flight. You will take the captain’s seat, while your instructor will serves as your first officer.</p>
				<p>The seats in the second row are for your friends or family. A maximum of 4 people may be present in the cockpit at a time: 3 guests and 1 instructor. Do not worry; you will do all the flying. Our pilot instructor will correct your actions ever so slightly and intervene only in the event of a mishap or hazard. When we say “hazard”, we mean only virtual hazards, of course. Your life will not be in danger at any time throughout your simulation experience.</p>
				<h2>Piloting Challenges</h2>
				<p>You will learn to appreciate how difficult a pilot’s job is. The sheer number of instruments and all the flickering lights will make your head spin. Every instrument on the panel in front of you will need a share of your attention. Add to that, our dynamic and moving platform, which imitates the vibration of the aircraft, every twist and turn, acceleration during takeoff and deceleration when landing.</p>
				<p>The panoramic display will show real-life landscapes that a pilot would see while up in the sky. Pilots who use the simulator to train for their flights say that the simulator’s visual experience is very similar to that of flying in real life.</p>
				<p>When you fly for the first time, you may be too occupied with controlling the aircraft and therefore won’t find the time to take in the scenery. In these situations, the guidance of a pilot instructor will be most valuable.</p>
				<h2>Professional Adviсe</h2>
				<p>Your instructor will adviсe you on flight sequences as well as the logic of piloting an aircraft. You will learn why you must warm up the engines, monitor your plane’s flaps, when you should switch on autopilot, and much more. By the end of your first session, you will have an understanding of what the crew of a passenger airliner does in various situations.</p>
				<p>Understanding the essence of piloting operations helps many people cope with aerophobia and uncontrollable panic attacks. Our pilot instructors are calm, collect, and a symbol of real professionalism. Visitors will feel confident in the presence of our pilot instructors and support staff. We pride ourselves on being role models for everyone, inside and outside of aviation.</p>
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
	<script src="{{ asset('js/deal.js?v=10') }}"></script>
@endpush