<input type="hidden" id="id" name="id" value="{{ $position->id }}">
<input type="hidden" id="contractor_id" name="contractor_id" value="{{ $position->deal ? $position->deal->contractor_id : 0 }}">
<input type="hidden" id="amount" name="amount" value="{{ $position->amount }}">
<input type="hidden" id="flight_simulator_id" name="flight_simulator_id" value="{{ $position->flight_simulator_id }}">

<div class="row">
	<div class="col-4">
		<div class="form-group">
			<label for="product_id">Product</label>
			<select class="form-control js-product" id="product_id" name="product_id">
				<option></option>
				@foreach($products ?? [] as $productTypeName => $productId)
					<optgroup label="{{ $productTypeName }}">
						@foreach($productId as $product)
							<option value="{{ $product->id }}" data-product_type_id="{{ $product->product_type_id }}" @if($product->id == $position->product_id) selected @endif>{{ $product->name }}</option>
						@endforeach
					</optgroup>
				@endforeach
			</select>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-8">
		<label for="comment">Comment</label>
		<textarea class="form-control" id="comment" name="comment" rows="2">{{ isset($position->data_json['comment']) ? $position->data_json['comment'] : '' }}</textarea>
	</div>
	<div class="col-4 text-right">
		<div class="form-group mt-4">
			{{--<div class="custom-control custom-switch custom-control-inline">
				<input type="checkbox" id="is_free" name="is_free" value="1" @if(!$position->amount) checked @endif class="custom-control-input">
				<label class="custom-control-label font-weight-normal" for="is_free">Free</label>
			</div>--}}
			<div id="amount-text" style="font-size: 30px;">
				<i class="fas fa-dollar-sign"></i> <span class="d-inline-block">{{ $position->amount }}</span>
			</div>
			<div id="tax-text" style="font-size: 18px;">
				<i class="fas fa-dollar-sign"></i> <span class="d-inline-block">{{ $position->tax }}</span>
			</div>
			<div id="total-amount-text" style="font-size: 18px;">
				<i class="fas fa-dollar-sign"></i> <span class="d-inline-block">{{ $position->total_amount }}</span>
			</div>
		</div>
	</div>
</div>
