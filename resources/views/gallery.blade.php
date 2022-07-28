@extends('layouts.master')

@section('title')
	{{ $page->meta_title }}
@stop
@section('description', $page->meta_description)

@section('content')
	<div class="breadcrumbs container"><a href="{{ url(Request::get('cityAlias') ?? '/') }}">@lang('main.home.title')</a> <span>@lang('main.galereya.title')</span></div>

	<article class="article">
		<div class="container">
			<h1 class="article-title">@lang('main.galereya.title')</h1>
			<div class="article-content">
				<div class="row">
					<div class="col-md-12 gallery">
						<div class="item">
							<div class="blockgallery">
								<div class="descr">
									<p>
										Here are some photos taken during actual sessions on the Dream Aero flight simulator. See the emotions of entry-level pilots in a real aircraft cockpit. Real flight controls and a full motion simulation provide for a vivid visual representation of what it’s really like to fly a passenger aircraft.
									</p>
								</div>
								<div class="filtr">
									<form class="ajax-form" data-ajax="galereya/">
										<button class="ajax-reset">@lang('main.galereya.all')</button>
										<div class="two-filtr">
											<div class="first-filtr">
												<input id="photo_type" name="gallery_type" value="photo" type="radio">
												<label for="photo_type">
													<span>PHOTOS</span>
												</label>
											</div>
											<div class="second-filtr">
												<input id="video_type" name="gallery_type" value="video" type="radio">
												<label for="video_type">
													<span>VIDEOS</span>
												</label>
											</div>
										</div>
									</form>
								</div>
							</div>
							<div style="clear: both;"></div>
							<div class="ajax-container">
								@foreach($gallery as $item)
									<a href="{{ (array_key_exists('video_url', $item->data_json) && $item->data_json['video_url']) ? $item->data_json['video_url'] : (array_key_exists('photo_preview_file_path', $item->data_json) ? '/upload/' . $item->data_json['photo_preview_file_path'] : '#') }}" class="fancybox @if(array_key_exists('video_url', $item->data_json) && $item->data_json['video_url']) is_video @else is_photo @endif" @if(array_key_exists('video_url', $item->data_json) && $item->data_json['video_url']) data-fancybox-type="iframe" @endif rel="gallery1" title="">
										<div class="ajax-item vilet" style="background: #ebebef url('/upload/{{ array_key_exists('photo_preview_file_path', $item->data_json) ? $item->data_json['photo_preview_file_path'] : '' }}') center center / contain no-repeat;">
											@if(array_key_exists('video_url', $item->data_json) && $item->data_json['video_url'])
												<img src="{{ asset('img/play.png') }}" class="playimg" alt="">
											@endif
											<span>{{ $item->title }}</span>
										</div>
									</a>
								@endforeach
								<p style="margin-top: 80px;"></p>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</article>

	@include('forms.question')
@endsection

@push('css')
	<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
	<link rel="stylesheet" href="{{ asset('css/jquery.fancybox.css') }}">
	<link rel="stylesheet" href="{{ asset('css/jquery.datetimepicker.min.css') }}">
	<style>
		.dag-white-line {
			width: 100%;
			height: 2px;
			background-color: #fff;
		}
		#dag-content{
			background-color:#FF8200;
			color: white;
		}
		.playimg{
			width: 50%;
			display: block;
			margin: 10% auto !important;
		}
		@media screen and (max-width: 991px) and (min-width: 590px) {
			.playimg{
				width: 35%;
				margin: 0 auto !important;
			}
		}
		@media screen and (max-width: 590px) and (min-width: 454px) {
			.playimg{
				margin: 10% auto !important ;
			}
		}
		@media screen and (max-width: 454px) {
			.playimg{
				margin: 20% auto !important;
			}
		}
		#dag-content .dag-guests {
			margin: 60px 0 20px -30px;
		}
		#dag-content .dag-guests .guests {
			text-align: center;
		}
		#dag-content .dag-guests .guests .guest {
			display: inline-block;
			width: calc(100% - 30px);
			margin-left: 30px;
		}
		@media screen and (min-width: 800px){
			#dag-content .dag-guests .guests .guest {
				display: inline-block;
				width: 300px;
				margin-left: 30px;
			}
		}
		#dag-content .dag-guests .guests .guest img {
			width: 100%;
			height: 267px;
			object-fit: cover;
			object-position: top;

		}
		#dag-content .dag-guests .guests .title {
			margin: 10px 10px 20px;
			font-size: 18px;
			line-height: 20px;
			color: #fff;
			float: left;
		}
		#dag-content .dag-guests .guests .title img {
			height: 20px;
			width: 20px;
			float: left;
			margin-right: 6px;
			margin-top: -3px;
		}
		#dag-content .dag-guests .guests a{
			color: white;
		}
		#dag-content .dag-guests .guests a:hover,
		#dag-content .dag-guests .guests a:active {
			color: white;
			text-decoration: underline;
		}
	</style>
@endpush

@push('scripts')
	<script src="{{ asset('js/jquery.datetimepicker.full.min.js') }}"></script>
	<script src="{{ asset('js/jquery.fancybox.pack.js') }}"></script>
	<script>
		$(function() {
			$(document).on('click', 'input[name="gallery_type"]', function() {
				if ($('#video_type').is(':checked')) {
					$('a.is_video').attr('style', 'display: inline-block !important');
					$('a.is_photo').attr('style', 'display: none !important');
				} else if ($('#photo_type').is(':checked')) {
					$('a.is_photo').attr('style', 'display: inline-block !important');
					$('a.is_video').attr('style', 'display: none !important');
				}
			});
		});
	</script>
@endpush