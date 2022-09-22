@extends('layouts.master')

@section('title')
	{{ $page->meta_title }}
@stop
@section('description', $page->meta_description)

@section('content')
	<div class="breadcrumbs container"><a href="{{ url($city->alias ?? '/') }}">@lang('main.home.title')</a> <span>@lang('main.contacts.title')</span></div>

	<div class="contacts">
		<div class="container">
			<h1 class="block-title">@lang('main.contacts.title')</h1>
		</div>

		{{--@foreach($locations as $location)
			<a href="{{ url('#location-' . $location->alias) }}" class="anchor">
				{{ $location->name }}
			</a>
		@endforeach--}}

		<div class="clear"></div>

		@foreach($locations as $index => $location)
			<section id="location-{{ $location->alias }}" @if (!$loop->first) style="margin-top: 65px;" @endif>
				{{--<h3>
					<strong>
						@lang('main.contacts.авиатренажерный-центр')
						@foreach($location->simulators ?? [] as $simulator)
							{{ $simulator->name }}
							@if(!$loop->last) / @endif
						@endforeach
					</strong>
				</h3>--}}

				@if ($location->data_json['map_link'])
					<div class="map" id="map-{{ $location->alias }}" @if ($loop->even) style="float: right;" @endif>
						<iframe class="youvideo" src="{{ $location->data_json['map_link'] }}" width="600" height="450" frameborder="0" style="border: 0;" allowfullscreen></iframe>
					</div>
				@endif

				<div class="contacts-inner">
					<div class="contacts-inner-inner">
						<ul class="contacts-list">
							@if (array_key_exists('address', $location->data_json) && $location->data_json['address'])
								<li class="address">{!! $location->data_json['address'] !!}</li>
							@endif

							@if (array_key_exists('working_hours', $location->data_json) && $location->data_json['working_hours'])
								<li class="schedule">{!! $location->data_json['working_hours'] !!}</li>
							@endif

							@if (array_key_exists('phone', $location->data_json) && $location->data_json['phone'])
								<li class="phone">{{ $location->data_json['phone'] }}</li>
							@endif

							@if (array_key_exists('email', $location->data_json) && $location->data_json['email'])
								<li class="email">{{ $location->data_json['email'] }}</li>
							@endif

							@if (array_key_exists('skype', $location->data_json) && $location->data_json['skype'])
								<li class="skype">{{ $location->data_json['skype'] }}</li>
							@endif
						</ul>

						@if (array_key_exists('scheme_file_path', $location->data_json) && $location->data_json['scheme_file_path'])
							<a href="javascript: void(0)" class="contacts-button button-pipaluk button-pipaluk-white popup-with-form" data-popup-type="scheme" data-alias="{{ $location->id }}"><i>@lang('main.contacts.как-нас-найти')</i></a>
						@endif

						<a href="javascript: void(0)" class="contacts-button button-pipaluk button-pipaluk-white popup-with-form" data-popup-type="callback"><i>@lang('main.contacts.заказать-звонок')</i></a>
						<br/>
					</div>
				</div>
			</section>
			<div style="clear: both;"></div>
		@endforeach
	</div>
@endsection

@push('css')
	@if (request()->getHost() == env('DOMAIN_SITE2'))
		<link rel="stylesheet" href="{{ asset('css/fly-737/contactstyle.css') }}">
	@else
		<link rel="stylesheet" href="{{ asset('css/contactstyle.css') }}">
	@endif
	<style>
		.popup-map {
			max-width: 930px;
			padding: 55px 106px 50px;
		}
		.popup-map img {
			max-width: 100%;
			height: inherit;
		}
		@media screen and (max-width: 767px) {
			.map {
				float: none;
				width: 100%;
				min-height: 300px;
			}
		}
		@media screen and (max-width: 767px) {
			.contacts-inner {
				min-height: auto;
				float: none;
				width: 100%;
				padding-top: 30px;
				padding-bottom: 30px;
				padding-left: 15px;
			}
		}
	</style>
@endpush