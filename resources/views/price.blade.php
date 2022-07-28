@extends('layouts.master')

@section('title')
	{{ $page->meta_title }}
@stop
@section('description', $page->meta_description)

@section('content')
	<div class="breadcrumbs container"><a href="{{ url(Request::get('cityAlias') ?? '/') }}">@lang('main.home.title')</a> <span>@lang('main.price.title')</span></div>

	<article class="article">
		<div class="container">
			<h2 class="block-title">@lang('main.price.title')</h2>
			<div class="article-content">
				<div class="row">
					<div class="col-md-12 price">
						<div class="prices">
							<div class="left-price">
								<div class="top-inf">
									<p class="bold">Book your time slot</p>
									<p>Vouchers holders may reserve any time slot for their flight. Book your session in advance for the most available options. Vouchers are valid for 6 months from the date of purchase.</p>
								</div>
								<div class="bottom-inf">
									<p class="bold">Price is up to 3 people</p>
									<p>Our prices are the same no matter the number of your crew. Fly alone or fly with up to two of your friends. While flying, you will be joined by an experienced pilot instructor who will provide support and guide you through your journey.</p>
								</div>
							</div>

							<div class="right-price">
								<div {{--class="tabs"--}}>
									{{--<div class="flexdiv">
										<ul class="tabs__caption">
											@foreach($productTypes as $productType)
												@if(!in_array($productType->alias, [app('\App\Models\ProductType')::REGULAR_ALIAS, app('\App\Models\ProductType')::ULTIMATE_ALIAS]))
													@continue;
												@endif
												<li class="@if($productType->alias == app('\App\Models\ProductType')::REGULAR_ALIAS) active @endif">
													<p style="text-transform: uppercase;">{{ $productType->name }}</p>
													<small>{{ $productType->alias == app('\App\Models\ProductType')::REGULAR_ALIAS ? 'weekdays' : 'any day' }}</small>
												</li>
											@endforeach
										</ul>
									</div>--}}

									@foreach($productTypes as $productType)
										@if(!in_array($productType->alias,
											[
												app('\App\Models\ProductType')::REGULAR_ALIAS,
												/*app('\App\Models\ProductType')::ULTIMATE_ALIAS,*/
											]
										))
											@continue;
										@endif

										<div class="tabs__content @if($productType->alias == app('\App\Models\ProductType')::REGULAR_ALIAS) active @endif">
											{{--<p class="stars"> <i>*</i> Regular - Valid from Monday through Friday. Not valid on weekends and holidays. Ultimate - Valid any day including holidays.</p>--}}

											@foreach($products[mb_strtoupper($productType->alias)] ?? [] as $productAlias => $product)
												<div class="block-price">
													@if($product['is_hit'])
														<span>@lang('main.price.хит-продаж')</span>
													@endif
													<p class="title">
														{{ $product['public_name'] }}
													</p>
													<p class="time">{{ $product['duration'] }} @lang('main.price.мин')</p>
													@if($product['icon_file_path'])
														<img src="{{ '/upload/' . $product['icon_file_path'] }}" alt="">
													@endif
													<div class="spblock" style="position: relative;">
														@if($product['base_price'] != $product['price'])
															<div style="height: 70px;">
																<div>
																	<b class="strikethrough" style="font-size: 18px;">
																		{{ $product['currency'] }}{{ $product['base_price'] }}
																	</b>
																</div>
																<div style="margin-top: 10px;">
																	<b class="price-sert">
																		{{ $product['currency'] }}{{ $product['price'] }}
																	</b>
																</div>
															</div>
														@else
															<div style="height: 70px;">
																<b class="price-sert">
																	{{ $product['currency'] }}{{ $product['base_price'] }}
																</b>
															</div>
														@endif
													</div>
													<a href="{{ url('#popup') }}" class="bron button-pipaluk button-pipaluk-orange popup-with-form form_open" data-modal="certificate" data-product-alias="{{ $product['alias'] }}"><i>Buy Now</i></a>
												</div>
											@endforeach
										</div>
									@endforeach
								</div>
							</div>
						</div>

						<br>
						<br>

						<h4>BE READY FOR YOUR FLIGHTs</h4>

						<div class="row download">
							<div class="col-md-4">
								<p>Select the best <a href="{{ url($cityAlias . '/flight-options') }}">flight</a> for you</p>
							</div>
							<div class="col-md-4">
								<p>Carefully read through <a href="{{ url($cityAlias . '/rules') }}">the code of conduct</a> for our guests</p>
							</div>
							<div class="col-md-4">
								<p>View our <a href="{{ url($cityAlias . '/prices#fbri') }}">pre-flight briefing</a> to better prepare you for takeoff</p>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="pr facts pages" id="home" data-type="background" data-speed="20" style="background-position: 100% 92.5px;">
			<div class="container">
				<h2 class="block-title">@lang('main.price.курс-пилота')</h2>
				<ul class="row bacground">
					<li class="col-md-6 wow fadeInUp" data-wow-delay="0" data-wow-duration="2s" style="text-align: center;visibility: visible;animation-duration: 2s;animation-name: fadeInUp;">
						<div class="ico"><img src="{{ asset('img/circle.png') }}" alt=""></div>
						<span>6 hours</span>
					</li>
					<li class="col-md-6 wow fadeInUp" data-wow-delay="0" data-wow-duration="2s" style="text-align: center;visibility: visible;animation-duration: 2s;animation-name: fadeInUp;">
						<div class="ico"><img src="{{ asset('img/docum.png') }}" alt=""></div>
						<span>Present</span>
					</li>
				</ul>
			</div>
		</div>

		<div class="conteiner-min">
			<div class="tabs2">
				<ul class="tabs2__caption">
					@foreach($products[mb_strtoupper(app('\App\Models\ProductType')::COURSES_ALIAS)] ?? [] as $productAlias => $product)
						@if(!in_array($product['alias'], ['basic', 'advanced', 'expert']))
							@continue
						@endif

						<li @if($product['alias'] == 'basic') class="active" @endif>
							{{ mb_strtoupper($product['name']) }}
						</li>
					@endforeach
				</ul>
				@foreach($products[mb_strtoupper(app('\App\Models\ProductType')::COURSES_ALIAS)] ?? [] as $productAlias => $product)
					@if(!in_array($product['alias'], ['basic', 'advanced', 'expert']))
						@continue
					@endif

					<div class="tabs2__content @if($product['alias'] == 'basic') active @endif">
						@if($product['alias'] == 'basic')
							<p>The <strong>BASIC</strong> course gives you:</p>
							<ul>
								<li>Aircraft aerodynamics</li>
								<li>Boeing 737-800 construction</li>
								<li>Basic rules and limitations for the flight</li>
								<li>In-flight procedures</li>
								<li>Visual and instrumental instruction</li>
								<li>Takeoff and landing instruction</li>
							</ul>
						@elseif($product['alias'] == 'advanced')
							<p>The <strong>ADVANCED</strong> course gives you:</p>
							<ul>
								<li>Aircraft aerodynamics</li>
								<li>Understanding the design of Boeing 737-800</li>
								<li>Basic rules and limitations for the flight</li>
								<li>In-flight procedures</li>
								<li>Visual and instrumental instruction</li>
								<li>Takeoff and landing instruction</li>
								<li>How to read and navigate using Jeppesen charts</li>
								<li>What is SOP and why it's used</li>
								<li>Basics of navigation</li>
								<li>Cruise procedures</li>
								<li>METAR weather code</li>
							</ul>
						@elseif($product['alias'] == 'expert')
							@lang('main.price.программа-expert')
						@endif

						<div class="block-price ather">
							<p class="title">@if(App::isLocale('ru')) @lang('main.price.курс-пилота2') @endif {{ mb_strtoupper($product['name']) }} @if(App::isLocale('en')) @lang('main.price.курс-пилота2') @endif</p>
							<p class="time">{{ $product['duration'] / 60 }} @lang('main.price.часов')</p>
							@if($product['icon_file_path'])
								<img src="{{ '/upload/' . $product['icon_file_path'] }}" alt="">
							@endif
							@if($product['base_price'] != $product['price'])
								<div style="height: 70px;">
									<div>
										<b class="strikethrough" style="font-size: 18px;">
											{{ $product['currency'] }}{{ $product['base_price'] }}
										</b>
									</div>
									<div style="margin-top: 10px;">
										<b class="price-sert">
											{{ $product['currency'] }}{{ $product['price'] }}
										</b>
									</div>
								</div>
							@else
								<div style="height: 70px;padding-top: 30px;">
									<b class="price-sert">
										{{ $product['currency'] }}{{ $product['base_price'] }}
									</b>
								</div>
							@endif
							<a href="{{ url('#popup') }}" class="obtain-button obtain-button button-pipaluk button-pipaluk-orange popup-with-form form_open" data-modal="certificate" data-product-alias="{{ $product['alias'] }}"><i>Buy Now</i></a>
						</div>
					</div>
				@endforeach
			</div>

			<div class="tabs2">
				<ul class="tabs2__caption">
					@foreach($products[mb_strtoupper(app('\App\Models\ProductType')::COURSES_ALIAS)] ?? [] as $productAlias => $product)
						@if(!in_array($product['alias'], ['kids_school']))
							@continue
						@endif

						<li class="active">
							Kid's
						</li>
					@endforeach
				</ul>
				@foreach($products[mb_strtoupper(app('\App\Models\ProductType')::COURSES_ALIAS)] ?? [] as $productAlias => $product)
					@if(!in_array($product['alias'], ['kids_school']))
						@continue
					@endif

					<div class="tabs2__content active">
						<p><strong>Kid’s Pilot School</strong> will teach your kids on:</p>
						<ul>
							<li>how an airplanes fly</li>
							<li>how to control an airplane on the ground</li>
							<li>how to control an airplane in the air</li>
							<li>how to find an airport and runway</li>
							<li>how to land an airplane in VFR conditions</li>
							<li>how to land an airplane in IFR conditions</li>
						</ul>

						<div class="block-price ather">
							<p class="title">{{ mb_strtoupper($product['name']) }}</p>
							<p class="time">{{ $product['duration'] / 60 }} hours</p>
							@if($product['icon_file_path'])
								<img src="{{ '/upload/' . $product['icon_file_path'] }}" alt="">
							@endif
							@if($product['base_price'] != $product['price'])
								<div style="height: 70px;">
									<div>
										<b class="strikethrough" style="font-size: 18px;">
											{{ $product['currency'] }}{{ $product['base_price'] }}
										</b>
									</div>
									<div style="margin-top: 10px;">
										<b class="price-sert">
											{{ $product['currency'] }}{{ $product['price'] }}
										</b>
									</div>
								</div>
							@else
								<div style="height: 70px;padding-top: 30px;">
									<b class="price-sert">
										{{ $product['currency'] }}{{ $product['base_price'] }}
									</b>
								</div>
							@endif
							<a href="{{ url('#popup') }}" class="obtain-button obtain-button button-pipaluk button-pipaluk-orange popup-with-form form_open" data-modal="certificate" data-product-alias="{{ $product['alias'] }}"><i>Buy Now</i></a>
						</div>
					</div>
				@endforeach
			</div>
		</div>
	</article>

	<div class="relax" id="fbri">
		<div class="container">
			<div class="row">
				<div class="wow fadeInLeft" data-wow-duration="2s" style="visibility: visible; animation-duration: 2s; animation-name: fadeInLeft;">
					<h2 class="block-title">FLIGHT BRIEFING</h2>
					<div class="text">
						<p>For your convenience and to save time, learn the basics of the Boeing 737 NG and complete your briefing before your flight.</p>
						<p>&nbsp;</p>
						<div id="youtuber"><iframe class="youvideo" src="https://www.youtube.com/embed/IDBDqMvlvcI?rel=0&amp;autoplay=1&amp;mute=1" width="300" height="150" frameborder="0" allowfullscreen="allowfullscreen"></iframe></div>
						<div class="block-break" style="height: 30px; clear: both;">&nbsp;</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="stock under">
		<div class="container">
			<div class="row">
				<div class="col-md-8 wow fadeInLeft" data-wow-duration="2s" style="visibility: visible; animation-duration: 2s; animation-name: fadeInLeft;">
					<h2 class="block-title">SPECIAL OFFERS</h2>
					<div class="text">
						<p>Weekdays happy hours from 12 PM to 2 PM get 15 minutes additional for free. Ready! Steady! Fly!<br><br>Get a 20% discount if you visit us on your birthday, 3 days before or 3 days after your birthday. ID proof is required.&nbsp;</p>
						<p>Get 25% discount when purchasing 4 or more Gift Certificates.</p>
					</div>
				</div>
				<div class="col-md-4">
					<div class="img wow fadeInRight" data-wow-delay="1s" data-wow-duration="2s" style="visibility: visible; animation-duration: 2s; animation-delay: 1s; animation-name: fadeInRight;">
						<img src="{{ asset('img/bplane2.png') }}" alt="">
						<a href="{{ url('#popup') }}" class="button-pipaluk button-pipaluk-orange popup-with-form" data-popup-type="callback">
							<i>I AM INTERESTED</i>
						</a>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection

@push('css')
	<link rel="stylesheet" href="{{ asset('css/pricestyle.css?v=' . time()) }}">
	<link rel="stylesheet" href="{{ asset('css/jquery.datetimepicker.min.css') }}">
	<style>
		.strikethrough {
			position: relative;
		}
		.strikethrough:before {
			border-bottom: 3px solid red;
			position: absolute;
			content: "";
			width: 50px;
			height: 15px;
			transform: rotate(-7deg);
		}
		.block-price {
			border: 1px solid #f7f7f9;
			background-color: #fff;
			min-height: 347px;
			display: inline-block;
			width: 31%;
			text-align: center;
			margin-right: 1.5%;
			margin-bottom: 53px;
			vertical-align: top;
			position: relative;
		}
		@media screen and (max-width: 767px) {
			.block-price {
				width: 100%;
				max-width: 300px;
			}
		}
		@media screen and (max-width: 425px) {
			.block-price {
				width: 100%;
			}
		}
		.block-price:hover {
			border: 1px solid red;
			background-color: #fff;
		}
		.tabs__content {
			padding-bottom: 0;
		}
	</style>
@endpush

@push('scripts')
	<script src="{{ asset('js/jquery.datetimepicker.full.min.js') }}"></script>
	<script src="{{ asset('js/deal.js?v=' . time()) }}"></script>
@endpush