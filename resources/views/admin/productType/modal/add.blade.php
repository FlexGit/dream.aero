<input type="hidden" id="id" name="id">
<div class="form-group">
	<label for="name">Name</label>
	<input type="text" class="form-control" id="name" name="name" placeholder="Name">
</div>
<div class="form-group">
	<label for="alias">Alias</label>
	<input type="text" class="form-control" id="alias" name="alias" placeholder="Alias">
</div>
<div class="form-group">
	<label for="is_tariff">Is tariff</label>
	<select class="form-control" id="is_tariff" name="is_tariff">
		<option value="1" selected>Yes</option>
		<option value="0">No</option>
	</select>
</div>
<div class="form-group js-duration-container">
	<label for="duration">Duration</label>
	<select class="form-control" id="duration" name="duration[]" multiple="multiple">
		@foreach($durations ?? [] as $duration)
			<option value="{{ $duration }}">{{ $duration }}</option>
		@endforeach
	</select>
</div>
<div class="form-group">
	<label for="tax">Vat, %</label>
	<input type="text" class="form-control" id="tax" name="tax" placeholder="Vat">
</div>
<div class="form-group">
	<label for="is_active">Is active</label>
	<select class="form-control" id="is_active" name="is_active">
		<option value="1" selected>Yes</option>
		<option value="0">No</option>
	</select>
</div>
