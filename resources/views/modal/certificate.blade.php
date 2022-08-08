<div>
	<p class="popup-description">
		Purchase a flight voucher.
		<br>
		Fill out a couple of fields for online voucher purchase
	</p>
	<h2 class="text-center js-product-name" style="height: 33px;"></h2>
	<form id="payment_form">
		<fieldset>
			<div class="row">
				<div class="col-md-12 text-right">
					<div>
						<span class="nice-select-label city">City: <b>{{ $city ? $city->name : '' }}</b></span>
					</div>
				</div>
			</div>

			@if($product)
				<input type="hidden" id="product_id" name="product_id" value="{{ $product->id }}">
			@else
				<div class="row">
					<div class="col-md-12">
						<select id="product_id" name="product_id" class="popup-input">
							<option value="">Select flight option</option>
							@php($productTypeName = '')
							@foreach($products ?? [] as $productItem)
								@if($productItem->productType && (in_array($productItem->productType->alias, [app('\App\Models\ProductType')::VIP_ALIAS, app('\App\Models\ProductType')::SERVICES_ALIAS, app('\App\Models\ProductType')::ULTIMATE_ALIAS])))
									@continue
								@endif
								<option value="{{ $productItem->id }}" data-product-type-alias="{{ $productItem->productType->alias }}" data-product-duration="{{ $productItem->duration }}">
									@if($productItem->productType->alias == app('\App\Models\ProductType')::COURSES_ALIAS)
										{{ $productItem->public_name }} ({{ $productItem->duration / 60 . ' hours' }})
									@else
										{{ $productItem->duration . ' min' }}
									@endif
								</option>
							@endforeach
						</select>
					</div>
				</div>
			@endif

			<div class="row">
				<div class="col-md-6">
					<input type="text" id="name" name="name" class="popup-input" placeholder="@lang('main.modal-certificate.имя')">
				</div>
				<div class="col-md-6">
					<input type="tel" id="phone" name="phone" class="popup-input" placeholder="YOUR PHONE NUMBER">
				</div>
			</div>

			<div class="row">
				<div class="col-md-12">
					<input type="email" id="email" name="email" class="popup-input" placeholder="@lang('main.modal-certificate.email')">
				</div>
			</div>

			@if(($product && $product->productType && in_array($product->productType->alias, [app('\App\Models\ProductType')::REGULAR_ALIAS, app('\App\Models\ProductType')::ULTIMATE_ALIAS])) || !$product)
				<div class="row switch_container promocode_container">
					<div style="display: flex;">
						<div class="switch_box" style="margin-bottom: 10px;">
							<label class="switch">
								<input type="checkbox" id="has_promocode" name="has_promocode" class="edit_field" value="1">
								<span class="slider round"></span>
							</label><span>@lang('main.modal-certificate.есть-промокод')</span>
						</div>
						<div style="display: flex;width: 100%;">
							<div style="width: 100%;">
								<input type="text" id="promocode" name="promocode" class="popup-input" placeholder="Enter a promo code" data-no-product-error="@lang('main.modal-certificate.выберите-продолжительность-полета')" style="display: none;margin-bottom: 0;">
							</div>
							<button type="button" class="popup-submit popup-small-button button-pipaluk button-pipaluk-orange js-promocode-btn" style="display: none;width: 35px;"><i>Ok</i></button>
							<svg width="24" height="24" fill="none" xmlns="http://www.w3.org/2000/svg" class="close-btn js-promocode-remove" style="display: none;"><path d="M12 10.587l6.293-6.294a1 1 0 111.414 1.414l-6.293 6.295 6.293 6.294a1 1 0 11-1.414 1.414L12 13.416 5.707 19.71a1 1 0 01-1.414-1.414l6.293-6.294-6.293-6.295a1 1 0 111.414-1.414L12 10.587z" fill="currentColor"></path></svg>
						</div>
					</div>
					{{--<small class="promocode_note" style="display: none;">* @lang('main.modal-certificate.не-суммируется-с-другими-акциями-и-предложениями')</small>--}}
				</div>

				<div class="row switch_container">
					<div style="display: flex;">
						<div class="switch_box" style="margin-bottom: 10px;">
							<label class="switch">
								<input type="checkbox" id="weekends" name="weekends" class="edit_field" value="1">
								<span class="slider round"></span>
							</label><span>Weekends</span><img src="{{ asset('img/circle-question-regular.svg') }}" class="help" data-tippy-content="Option to fly on any day, including weekend" alt="help" width="20">
						</div>
					</div>
				</div>

				<div class="row switch_container">
					<div style="display: flex;">
						<div class="switch_box" style="margin-bottom: 10px;">
							<label class="switch">
								<input type="checkbox" id="birthday" name="birthday" class="edit_field" value="1">
								<span class="slider round"></span>
							</label><span>Birthday</span><img src="{{ asset('img/circle-question-regular.svg') }}" class="help" data-tippy-content="Birthday 20% Discount" alt="help" width="20">
						</div>
					</div>
				</div>
			@endif

			<div class="amount-container text-right">
				<div class="amount">
					<span>Subtotal: <span class="js-amount">{{ $city ? $city->currency->name : '' }}0</span></span>
				</div>
				<div class="amount">
					<span style="font-size: 18px;">Tax: <span class="js-tax">{{ $city ? $city->currency->name : '' }}0</span></span>
				</div>
				<div class="amount" style="line-height: 0.5em;">
					<span style="font-size: 18px;">Total: <span class="js-total-amount">{{ $city ? $city->currency->name : '' }}0</span></span>
				</div>
			</div>

			<button type="button" class="popup-submit button-pipaluk button-pipaluk-orange js-card-btn" style="margin-top: 20px;"><i>PAY ONLINE</i></button>

			<div class="card-requisites hidden">
				<div class="row">
					<div class="col-md-12">
						<div class="card-wrapper" style="margin-top: 20px;"></div>
					</div>
				</div>
				<div class="row" style="margin-top: 40px;">
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

			<div class="consent-container hidden" style="display: flex; justify-content: center;">
				<div class="col-md-8 text-left">
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

			<button type="button" class="popup-submit button-pipaluk button-pipaluk-grey js-certificate-btn hidden" data-source="{{ app('\App\Models\Deal')::WEB_SOURCE }}" data-event_type="{{ app('\App\Models\Event')::EVENT_TYPE_DEAL }}" data-url="{{ route('dealCertificateStore') }}" style="margin-top: 20px;" disabled><i>SUBMIT</i></button>

			<input type="hidden" id="promocode_uuid">
		</fieldset>
	</form>
</div>
