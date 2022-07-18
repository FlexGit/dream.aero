<input type="hidden" id="id" name="id">
<div class="form-group">
	<label for="value">Value</label>
	<input type="number" class="form-control" id="value" name="value" placeholder="value">
</div>
<div class="form-group">
	<label for="is_fixed">Is fixed</label>
	<select class="form-control" id="is_fixed" name="is_fixed">
		<option value="1">Yes</option>
		<option value="0" selected>No</option>
	</select>
</div>
<div class="form-group hidden">
	<label for="currency_id">Currency</label>
	<select class="form-control" id="currency_id" name="currency_id">
		@foreach($currencies ?? [] as $currency)
			<option value="{{ $currency->id }}">{{ $currency->name }}</option>
		@endforeach
	</select>
</div>
<div class="form-group">
	<label for="is_active">Is active</label>
	<select class="form-control" id="is_active" name="is_active">
		<option value="1" selected>Yes</option>
		<option value="0">No</option>
	</select>
</div>
