<input type="hidden" id="id" name="id" value="{{ $productType->id }}">

<div class="form-group">
	<label for="name">Name</label>
	<input type="text" class="form-control" id="name" name="name" value="{{ $productType->name }}" placeholder="Name">
</div>
<div class="form-group">
	<label for="alias">Alias</label>
	<input type="text" class="form-control" id="alias" name="alias" value="{{ $productType->alias }}" placeholder="Alias">
</div>
<div class="form-group">
	<label for="is_tariff">Is tariff</label>
	<select class="form-control" id="is_tariff" name="is_tariff">
		<option value="1" @if($productType->is_tariff) selected @endif>Yes</option>
		<option value="0" @if(!$productType->is_tariff) selected @endif>No</option>
	</select>
</div>
<div class="form-group js-duration-container">
	<label for="duration">Duration</label>
	<select class="form-control" id="duration" name="duration[]" multiple="multiple">
		@foreach($durations ?? [] as $duration)
			<option value="{{ $duration }}" @if($productType->data_json['duration'] && in_array($duration, $productType->data_json['duration'])) selected @endif>{{ $duration }}</option>
		@endforeach
	</select>
</div>
<div class="form-group">
	<label for="tax">Vat, %</label>
	<input type="text" class="form-control" id="tax" name="tax" value="{{ $productType->tax }}" placeholder="Vat">
</div>
<div class="form-group">
	<label for="is_active">Is active</label>
	<select class="form-control" id="is_active" name="is_active">
		<option value="1" @if($productType->is_active) selected @endif>Yes</option>
		<option value="0" @if(!$productType->is_active) selected @endif>No</option>
	</select>
</div>
