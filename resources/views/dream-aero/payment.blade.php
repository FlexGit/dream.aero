@php
	$deal = $bill->deal ?? null;
	$product = $deal ? $deal->product : null;
@endphp

@extends('layouts.master')

@section('title')
	{{ $page->meta_title }}
@stop
@section('description', $page->meta_description)

@section('content')
	<div class="breadcrumbs container"><a href="{{ url(Request::get('cityAlias') ?? '/') }}">@lang('main.home.title')</a> <span>Invoice payment</span></div>

	<article class="article" style="margin-bottom: 50px;">
		<div class="container">
			<h1 class="article-title">Invoice # {{ $bill->number }}</h1>
			<div class="article-content" style="max-width: 800px;min-height: 250px;">
				@if($error)
					{{ $error }}
				@else
					<form id="payment_form">
						<fieldset>
							<div class="row">
								<div class="col-md-12 text-right">
									<span class="nice-select-label city">City: <b>{{ $city ? $city->name : '' }}</b></span>
								</div>
							</div>

							<div class="row">
								<div class="col-md-6">
									<input type="text" id="name" name="name" value="{{ $deal->name }}" class="popup-input" placeholder="@lang('main.modal-certificate.имя')" readonly>
								</div>
								<div class="col-md-6">
									<input type="text" id="phone" name="phone" value="{{ $deal->phone }}" class="popup-input" placeholder="PHONE NUMBER">
								</div>
							</div>

							<div class="row">
								<div class="col-md-6">
									<input type="text" id="email" name="email" value="{{ $deal->email }}" class="popup-input" placeholder="@lang('main.modal-certificate.email')">
								</div>
								<div class="col-md-6">
									<input type="text" id="product_name" name="product_name" value="{{ $product ? $product->name : '' }}" class="popup-input" placeholder="Product" readonly>
								</div>
							</div>

							<div class="amount-container text-right">
								<div class="amount">
									<span>SUBTOTAL: {{ $city ? $city->currency->name : '' }}<span>{{ number_format($bill->amount, 2, '.', ' ') }}</span></span>
								</div>
								<div class="amount">
									<span>TAX: {{ $city ? $city->currency->name : '' }}<span>{{ number_format($bill->tax, 2, '.', ' ') }}</span></span>
								</div>
								<div class="amount">
									<span>TOTAL: {{ $city ? $city->currency->name : '' }}<span>{{ number_format($bill->total_amount, 2, '.', ' ') }}</span></span>
								</div>
							</div>

							<div class="card-requisites">
								<div class="row">
									<div class="col-md-12">
										<div class="card-wrapper" style="margin-bottom: 40px;"></div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-6">
										<div>
											<input type="text" id="card_number" name="card_number" class="popup-input" placeholder="CARD NUMBER">
										</div>
									</div>
									<div class="col-md-6">
										<div>
											<input type="text" id="card_name" name="card_name" class="popup-input" placeholder="FULL NAME">
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-6">
										<div>
											<input type="text" id="expiration_date" name="expiration_date" class="popup-input" placeholder="MM/YYYY">
										</div>
									</div>
									<div class="col-md-6">
										<div>
											<input type="number" id="card_code" name="card_code" class="popup-input" placeholder="CVC">
										</div>
									</div>
								</div>
							</div>

							<div class="consent-container" style="display: flex; justify-content: center;">
								<div class="col-md-6 text-left">
									<label class="cont text-nowrap">
										I agree with <a href="{{ url(($city ? $city->alias : '') . '/rules') }}" target="_blank">the rules</a> for using the flight simulator
										<input type="checkbox" id="rules-consent" name="rules-consent" value="1">
										<span class="checkmark"></span>
									</label>
									<label class="cont">
										I have read <a href="{{ url(($city ? $city->alias : '') . '/privacy-policy') }}" target="_blank">the privacy and cookie policy</a>
										<input type="checkbox" id="policy-consent" name="policy-consent" value="1">
										<span class="checkmark"></span>
									</label>
								</div>
							</div>

							<div class="clearfix"></div>

							<div style="margin: 20px;">
								<div class="alert alert-success text-center hidden" role="alert"></div>
								<div class="alert alert-danger text-center hidden" role="alert"></div>
							</div>

							<button type="button" class="popup-submit button-pipaluk button-pipaluk-grey js-pay-btn" data-uuid="{{ $bill->uuid }}" style="margin-top: 20px;" disabled><i>SUBMIT</i></button>
						</fieldset>
					</form>
				@endif
			</div>
		</div>
	</article>

	{{--<article class="article">
		<div class="container">
			<h1 class="article-title">Payment</h1>
			<div class="article-content">
				<div class="row">
					<div class="item">
						<fieldset>
							@if($error)
								<p>{{ $error }}</p>
							@else
								<div class="popup popup-newreservation" id="popup">
									{!! $html !!}
									<label for="name">Name</label>
									<input type="text" id="name" value="{{ $deal->name }}" class="popup-input" readonly style="font-size: 14px;">
									<label for="amount">Amount</label>
									<input type="text" id="amount" value="{{ $bill->amount }}" class="popup-input" readonly style="font-size: 14px;">

									<div class="consent-container" style="display: flex; justify-content: center;">
										<div class="col-md-12 text-left">
											<label class="cont text-nowrap">
												I agree with <a href="{{ url(($city ? $city->alias : '') . '/rules') }}" target="_blank">the rules</a> for using the flight simulator
												<input type="checkbox" id="rules-consent" name="rules-consent" value="1">
												<span class="checkmark" style="top: 5px;"></span>
											</label>
											<label class="cont">
												I have read <a href="{{ url(($city ? $city->alias : '') . '/privacy-policy') }}" target="_blank">the privacy and cookie policy</a>
												<input type="checkbox" id="policy-consent" name="policy-consent" value="1">
												<span class="checkmark" style="top: 5px;"></span>
											</label>
										</div>
									</div>

									<div style="margin-top: 10px;">
										<div class="alert alert-success text-center hidden" role="alert"></div>
										<div class="alert alert-danger text-center hidden" role="alert"></div>
									</div>

									<button type="button" class="popup-submit button-pipaluk button-pipaluk-grey js-pay-btn" data-uuid="{{ $bill->uuid }}" style="margin-top: 20px;" disabled><i>@lang('main.common.оплатить')</i></button>
								</div>
							@endif
						</fieldset>
					</div>
				</div>
			</div>
		</div>
	</article>--}}
@endsection

@push('css')
	<link rel="stylesheet" href="{{ asset('css/jquery.datetimepicker.min.css') }}">
	<link rel="stylesheet" href="{{ asset('css/card.css') }}">
@endpush

@push('scripts')
	<script src="{{ asset('js/jquery.datetimepicker.full.min.js') }}"></script>
	<script src="{{ asset('js/jquery.card.js') }}"></script>
	<script src="{{ asset('js/deal.js?v=' . time()) }}"></script>

	<script>
		$(function() {
			$('#payment_form').card({
				container: '.card-wrapper',
				formSelectors: {
					numberInput: 'input#card_number',
					expiryInput: 'input#expiration_date',
					cvcInput: 'input#card_code',
					nameInput: 'input#card_name'
				}
			});

			$(document).on('change', 'input[name="rules-consent"], input[name="policy-consent"]', function() {
				var $popup = $(this).closest('form'),
					$btn = $popup.find('.js-pay-btn'),
					$secondCheckbox = ($(this).attr('name') === 'rules-consent') ? $('input[name="policy-consent"]') : $('input[name="rules-consent"]');

				if ($(this).is(':checked') && $secondCheckbox.is(':checked')) {
					$btn.removeClass('button-pipaluk-grey')
						.addClass('button-pipaluk-orange')
						.prop('disabled', false);
				} else {
					$btn.removeClass('button-pipaluk-orange')
						.addClass('button-pipaluk-grey')
						.prop('disabled', true);
				}
			});

			$(document).on('click', '.js-pay-btn', function() {
				var $btn = $(this),
					$popup = $(this).closest('form'),
					cardNumber = $popup.find('#card_number').val(),
					expirationDate = $popup.find('#expiration_date').val(),
					cardName = $popup.find('#card_name').val(),
					cardCode = $popup.find('#card_code').val(),
					$alertSuccess = $popup.find('.alert-success'),
					$alertError = $popup.find('.alert-danger');

				$alertSuccess.addClass('hidden');
				$alertError.text('').addClass('hidden');
				$('.field-error').removeClass('field-error');

				$btn.prop('disabled', true);

				$.ajax({
					url: '{{ route('paymentProceed') }}',
					type: 'POST',
					data: {
						'uuid': $(this).data('uuid'),
						'card_number': cardNumber.replace(/[^\d]/g, ""),
						'expiration_date': expirationDate.replace(/[^\d]/g, ""),
						'card_name': cardName,
						'card_code': cardCode.replace(/[^\d]/g, ""),
					},
					dataType: 'json',
					success: function (result) {
						//console.log(result);

						$btn.prop('disabled', false);

						if (result.status !== 'success') {
							if (result.reason) {
								$alertError.text(result.reason).removeClass('hidden');
							}
							if (result.errors) {
								const entries = Object.entries(result.errors);
								entries.forEach(function (item, key) {
									var fieldId = item[0],
										$field = $('#' + fieldId);
									if ($field.attr('id') === 'product_id') {
										$field.next('.nice-select').addClass('field-error');
									} else {
										$field.addClass('field-error');
									}
								});
							}
							return;
						}

						$popup.find('#card_number, #card_name, #expiration_date, #card_code').val('');
						$alertSuccess.html(result.message).removeClass('hidden');

						ym(49890730,'reachGoal','BookDC');
					}
				});
			});
		});
	</script>
@endpush
