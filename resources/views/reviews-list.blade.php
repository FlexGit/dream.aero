@extends('layouts.master')

@section('title')
	{{ $page->meta_title }}
@stop
@section('description', $page->meta_description)

@section('content')
	<div class="breadcrumbs container"><a href="{{ url(Request::get('cityAlias') ?? '/') }}">@lang('main.home.title')</a> <span>@lang('main.reviews.title')</span></div>

	<div class="news-list">
		<div class="container">
			<h1 class="block-title">@lang('main.reviews.title')</h1>
			<div class="article-content">
				<div class="row">
					<div class="col-md-12 price">
						<div class="reviews">
							<div class="container">
								<a href="javascript: void(0)" class="button button-pipaluk button-pipaluk-orange popup-with-form" data-popup-type="review"><i>@lang('main.reviews.оставить-отзыв')</i></a>
								<div id="pdopage">
									<div class="rows">
										@foreach($reviews as $item)
											<div class="item">
												<div class="row">
													<div class="col-md-7">
														<div class="reviews-item">
															<div class="reviews-body wow fadeIn" style="visibility: visible; animation-name: fadeIn;">
																<p class="reviews-text">
																	{{ strip_tags($item->preview_text) }}
																	@if($item->detail_text)
																		<br>
																		<em>
																			<b>@lang('main.reviews.ответ-администрации')</b>:
																			{{ strip_tags($item->detail_text) }}
																		</em>
																	@endif
																</p>
															</div>
														</div>
													</div>
													<div class="col-md-5">
														<div class="reviews-author wow fadeIn" data-wow-delay="1s" data-wow-duration="2s" style="visibility: visible; animation-duration: 2s; animation-delay: 1s; animation-name: fadeIn;">
															<span class="reviews-sent">sent: {{ $item->created_at->format('m-d-Y') }}</span>
														</div>
													</div>
												</div>
											</div>
											<hr>
										@endforeach
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection

@push('css')
	<link rel="stylesheet" href="{{ asset('css/newsstyle.css') }}">
	<style>
		.article-content .reviews {
			margin-top: 60px;
		}
		.article-content .item {
			display: block;
			color: #4d4d51;
			padding-top: 5%;
			font-size: 18px;
			line-height: 30px;
			letter-spacing: .25px;
		}
	</style>
@endpush

@push('scripts')
	<script>
		$(function(){
		});
	</script>
@endpush
