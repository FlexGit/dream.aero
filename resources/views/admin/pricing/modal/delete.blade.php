<input type="hidden" id="city_id" name="city_id" value="{{ $city->id }}">
<input type="hidden" id="product_id" name="product_id" value="{{ $product->id }}">
<div class="form-group">
	<label>Are you sure you want to delete the product {{ $product->name }} in city {{ $city->name }}?</label>
</div>
