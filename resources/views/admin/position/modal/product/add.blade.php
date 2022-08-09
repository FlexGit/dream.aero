<input type="hidden" id="id" name="id">
<input type="hidden" id="deal_id" name="deal_id" value="{{ $deal ? $deal->id : 0 }}">
<input type="hidden" id="contractor_id" name="contractor_id" value="{{ $deal ? $deal->contractor_id : 0 }}">
<input type="hidden" id="amount" name="amount">

<div class="row">
	<div class="col-4">
		<div class="form-group">
			<label for="product_id">Product</label>
			<select class="form-control js-product" id="product_id" name="product_id">
				<option></option>
				@foreach($products ?? [] as $productTypeName => $productId)
					{{--<optgroup label="{{ $productTypeName }}">--}}
						@foreach($productId as $product)
							<option value="{{ $product->id }}" data-product_type_id="{{ $product->product_type_id }}">{{ $product->name }}</option>
						@endforeach
					{{--</optgroup>--}}
				@endforeach
			</select>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-8">
		<label for="comment">Comment</label>
		<textarea class="form-control" id="comment" name="comment" rows="2"></textarea>
	</div>
	<div class="col-4 text-right">
		<div class="form-group mt-4">
			{{--<div class="custom-control custom-switch custom-control-inline">
				<input type="checkbox" id="is_free" name="is_free" value="1" class="custom-control-input">
				<label class="custom-control-label font-weight-normal" for="is_free">Free</label>
			</div>--}}
			<div id="amount-text" style="font-size: 30px;">
				<i class="fas fa-dollar-sign"></i> <span class="d-inline-block">0</span>
			</div>
			<div id="tax-text" style="font-size: 18px;">
				<i class="fas fa-dollar-sign"></i> <span class="d-inline-block">0</span>
			</div>
			<div id="total-amount-text" style="font-size: 18px;">
				<i class="fas fa-dollar-sign"></i> <span class="d-inline-block">0</span>
			</div>
		</div>
	</div>
</div>
