@extends('layouts.master')

@section('title')
	{{ $page->meta_title }}
@stop
@section('description', $page->meta_description)

@section('content')
	<div class="breadcrumbs container"><a href="{{ url(Request::get('cityAlias') ?? '/') }}">@lang('main.home.title')</a> <span>Rules for using the simulator</span></div>

	<article class="article">
		<div class="container">
			<h1 class="article-title">RULES FOR USING THE SIMULATOR</h1>
			<div class="article-content">
				<div class="row">
					<div class="item">
						<ul>
							<li>Children over the age of 6 may fly the Simulator only if accompanied by a parent or legal guardian. Read all of the rules to your child The Instructor may ask you to provide a document confirming the age of the child.</li>
							<li>The Instructor has the right to deny admission to any person who appears intoxicated or under the influence of narcotics, or whose medication which may cause them discomfort during the simulation.</li>
							<li>Maximum of 4 persons are allowed to the cockpit (3 visitors and a Dream Aero Instructor). The total weight of all persons during operation cannot exceed 650 pounds.</li>
							<li>Pregnant women, and persons with a heart condition, neck or back problems, who are subject to motion sickness, or who may have other health problems that may make them vulnerable to injury, must disclose their condition, and are not permitted to enter the Simulator.</li>
							<li>During the flight simulation, all participants must: (1) observe these and all other safety rules provided (2) use the safety equipment provided (seat belt/harness); and (3) follow all of the instructions of the Instructor. Failure to follow these rules and/or instructions will result in the termination of the simulation and no refund will provided.</li>
							<li>All personal belongings must be securely fastened during the flight simulation in order to avoid their self-movement inside the cockpit.</li>
							<li>Only one (l) adult at a time may be on the drawing bridge.</li>
						</ul>
						<p><strong>It is prohibited:</strong></p>
						<a name="birthday"></a>
						<ul>
							<li>For unauthorized persons to stay in the cockpit without an Instructor present.</li>
							<li>To bring food and animals into the Simulator.</li>
							<li>For unauthorized persons to stand within 6 feet of the base of the simulator platform.</li>
							<li>To use physical force to the control system of the Simulator, including its levers, buttons, switches, taps.</li>
							<li>To enter or exit the Simulator cockpit in any other way except using the drawing bridge (this rule does not apply to emergency situations). Persons violating this rule will be barred from the flight simulation and will forfeit the ticket price.</li>
						</ul>
						<p>
							<strong>Birthday 20% Discount Terms and Conditions:</strong>
						</p>
						<p><strong>Flight Purchases:</strong></p>
						<ul>
							<li>When you purchase a flight with a Birthday discount, you have to book an appointment at the time of the purchase. The appointment date can be either on the birthday or three days before or after the birthday.</li>
							<li>Proof of ID to verify the birthday is required at payment or prior to the flight. Failure to present ID will result in the full cost of the flight being charged and therefore you will reimburse to us the 20% discount</li>
							<li>If you reschedule the flight for a date that is either three days earlier or after your birthday then you will have to reimburse us the 20% discount</li>
							<li>If you purchase the “Regular" flight that is valid only on weekdays and you want to reschedule your flight to a weekend or holiday, then you have to pay the fare difference</li>
							<li>You can purchase and book the flight at any time period before your birthday</li>
							<li>The appointment cannot be booked without prior payment.</li>
						</ul>
						<p><strong>Gift Voucher Purchases:</strong></p>
						<ul>
							<li>You can purchase a Gift Certificate with the 20% Birthday Discount either on your birthday or 3 days before or 3 days after your birthday. Proof of ID should be presented upon purchase</li>
							<li>Failure to provide proof of ID will result in forfeiting the 20% discount</li>
							<li>The number of Gift Certificates that can be purchased is unlimited</li>
						</ul>
						<p><strong>All our offers are not valid in conjunction with other promotions or discounts</strong></p>
					</div>
				</div>
			</div>
		</div>
	</article>
@endsection
