@php
	$isActive = $isHit = $isBookingAllow = $isCertificatePurchaseAllow = $isDiscountBookingAllow = $isDiscountCertificatePurchaseAllow = 0;
	if($cityProduct) {
		$isActive = $cityProduct->is_active;
	}
@endphp

<input type="hidden" id="city_id" name="city_id" value="{{ $cityId }}">
<input type="hidden" id="product_id" name="product_id" value="{{ $product->id }}">

<div class="row">
	<div class="col">
		<div class="form-group">
			<label for="availability">Availability</label>
			<input type="number" class="form-control" id="availability" name="availability" value="{{ $cityProduct ? $cityProduct->availability : '' }}" placeholder="Availability">
		</div>
	</div>
	<div class="col">
		<div class="form-group">
			<label for="purchase_price">Purchase price</label>
			<input type="number" class="form-control" id="purchase_price" name="purchase_price" value="{{ $cityProduct ? $cityProduct->purchase_price : '' }}" placeholder="Purchase price">
		</div>
	</div>
	<div class="col">
		<div class="form-group">
			<label for="price">Selling price</label>
			<input type="number" class="form-control" id="price" name="price" value="{{ $cityProduct ? $cityProduct->price : '' }}" placeholder="Selling price">
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
</div>
