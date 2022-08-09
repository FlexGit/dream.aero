@php
	$isActive = $isHit = $isBookingAllow = $isCertificatePurchaseAllow = $isDiscountBookingAllow = $isDiscountCertificatePurchaseAllow = 0;
	if($cityProduct) {
		$isActive = $cityProduct->is_active;
		$data = $cityProduct ? (is_array($cityProduct->data_json) ? $cityProduct->data_json : json_decode($cityProduct->data_json, true)) : [];
		$isCertificatePurchaseAllow = array_key_exists('is_certificate_purchase_allow', $data) ? $data['is_certificate_purchase_allow'] : 0;
		$certificatePeriod = array_key_exists('certificate_period', $data) ? $data['certificate_period'] : 0;
	}
@endphp

<input type="hidden" id="city_id" name="city_id" value="{{ $cityId }}">
<input type="hidden" id="product_id" name="product_id" value="{{ $product->id }}">

<div class="row">
	<div class="col">
		<div class="form-group">
			<label for="price">Amount</label>
			<input type="number" class="form-control" id="price" name="price" value="{{ $cityProduct ? $cityProduct->price : '' }}" placeholder="Amount">
		</div>
	</div>
	<div class="col">
		<div class="form-group">
			<label for="discount_id">Discount</label>
			<select class="form-control" id="discount_id" name="discount_id">
				<option></option>
				@foreach($discounts ?? [] as $discount)
					<option value="{{ $discount->id }}" @if($cityProduct && $discount->id == $cityProduct->discount_id) selected @endif>{{ $discount->valueFormatted() }}</option>
				@endforeach
			</select>
		</div>
	</div>
	<div class="col">
		<div class="form-group">
			<label for="is_active">Is active</label>
			<select class="form-control" id="is_active" name="is_active">
				<option value="1" @if(!$cityProduct || ($cityProduct && $isActive)) selected @endif>Yes</option>
				<option value="0" @if($cityProduct && !$isActive) selected @endif>No</option>
			</select>
		</div>
	</div>
	{{--@if(!in_array($product->productType->alias, [app('\App\Models\ProductType')::SERVICES_ALIAS]))
		<div class="col">
			<div class="form-group">
				<label for="certificate_period">Validity</label>
				<select class="form-control" id="certificate_period" name="certificate_period">
					<option value="6" @if($cityProduct && $certificatePeriod == 6) selected @endif>6 months</option>
					<option value="12" @if($cityProduct && $certificatePeriod == 12) selected @endif>1 year</option>
					<option value="0" @if($cityProduct && !$certificatePeriod) selected @endif>termless</option>
				</select>
			</div>
		</div>
	@endif--}}
</div>
