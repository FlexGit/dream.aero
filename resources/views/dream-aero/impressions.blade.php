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