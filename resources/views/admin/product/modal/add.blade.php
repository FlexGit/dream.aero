<input type="hidden" id="id" name="id">

<div class="form-group">
	<label for="name">Name</label>
	<input type="text" class="form-control" id="name" name="name" placeholder="Name">
</div>
<div class="form-group">
	<label for="public_name">Public name</label>
	<input type="text" class="form-control" id="public_name" name="public_name" placeholder="Public name">
</div>
<div class="form-group">
	<label for="alias">Alias</label>
	<input type="text" class="form-control" id="alias" name="alias" placeholder="Alias">
</div>
<div class="form-group">
	<label for="product_type_id">Product type</label>
	<select class="form-control" id="product_type_id" name="product_type_id">
		<option></option>
		@foreach($productTypes ?? [] as $productType)
		<option value="{{ $productType->id }}" data-duration="{{ array_key_exists('duration', $productType->data_json) ? json_encode($productType->data_json['duration']) : json_encode([]) }}" data-with_user="{{ array_key_exists('with_user', $productType->data_json) ? (bool)$productType->data_json['with_user'] : false }}">{{ $productType->name }}</option>
		@endforeach
	</select>
</div>
<div class="form-group">
	<label for="duration">Duration, min</label>
	<select class="form-control" id="duration" name="duration">
	</select>
</div>
<div class="form-group">
	<label for="user_id">Pilot (for VIP product type)</label>
	<select class="form-control" id="user_id" name="user_id">
		@foreach($pilots ?? [] as $pilot)
			<option></option>
			<option value="{{ $pilot->id }}">{{ $pilot->fio() }}</option>
		@endforeach
	</select>
</div>
<div class="form-group">
	<label>Icon file path</label>
	<div class="custom-file">
		<input type="file" class="custom-file-input" id="icon_file" name="icon_file">
		<label class="custom-file-label" for="icon_file">Choose a file</label>
	</div>
</div>
<div class="form-group">
	<label for="description">Description</label>
	<textarea class="form-control" id="description" name="description" rows="5"></textarea>
</div>
<div class="form-group">
	<label for="is_active">Is active</label>
	<select class="form-control form-control-sm" id="is_active" name="is_active">
		<option value="1" selected>Yes</option>
		<option value="0">No</option>
	</select>
</div>
