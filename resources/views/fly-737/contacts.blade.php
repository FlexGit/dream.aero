@extends('layouts.master')

@section('title')
	{{ $page->meta_title }}
@stop
@section('description', $page->meta_description)

@section('content')
	<div class="breadcrumbs container"><a href="{{ url('/') }}">@lang('main-fly737.home.title')</a> <span>@lang('main-fly737.contacts.title')</span></div>

	<div class="contacts">
		<div class="container">
			<h1 class="block-title">@lang('main-fly737.contacts.title')</h1>
		</div>
		<div class="clear"></div>
		<section>
			<div class="map">
				<iframe class="youvideo" src="https://www.google.com/maps/d/embed?mid=1a3SwxWO6A6HW9ZNuv_pXnvI4FY_PFmM&ehbc=2E312F" width="100%" height="600" frameborder="0" style="border: 0;" allowfullscreen=""></iframe>
			</div>
			<div class="contacts-inner">
				<div class="contacts-inner-inner">
					<ul class="contacts-list">
						<li class="address">600 Brickell Ave, Miami, FL 33131, United States, (202) 913-8453</li>
						{{--<li class="schedule"></li>
						<li class="email"></li>--}}
					</ul>
					{{--<a href="javascript: void(0)" class="contacts-button button-pipaluk button-pipaluk-white popup-with-form" data-popup-type="callback"><i>Request a call</i></a>--}}
					<br>
				</div>
			</div>
		</section>
		<div style="clear: both;"></div>
	</div>
@endsection

@push('css')
	<link rel="stylesheet" href="{{ asset('css/fly-737/contactstyle.css?v=2') }}">
@endpush