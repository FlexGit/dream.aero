<input type="hidden" id="id" name="id">
<div class="form-group">
	<label for="name">Name</label>
	<input type="text" class="form-control form-control-sm" id="name" name="name" placeholder="Name">
</div>
<div class="form-group">
	<label for="alias">Alias</label>
	<input type="text" class="form-control form-control-sm" id="alias" name="alias" placeholder="Alias">
</div>
{{--<div class="form-group">
	<label for="legal_entity_id">Юридическое лицо</label>
	<select id="legal_entity_id" name="legal_entity_id" class="form-control form-control-sm">
		<option></option>
		@foreach($legalEntities as $legalEntity)
			<option value="{{ $legalEntity->id }}">{{ $legalEntity->name }}</option>
		@endforeach
	</select>
</div>--}}
{{--<div class="form-group">
	<label for="city_id">City</label>
	<select id="city_id" name="city_id" class="form-control form-control-sm">
		<option></option>
		@foreach($cities as $city)
			<option value="{{ $city->id }}">{{ $city->name }}</option>
		@endforeach
	</select>
</div>--}}
<div class="form-group">
	<label for="address">Address</label>
	<textarea class="form-control form-control-sm" id="address" name="address" rows="2"></textarea>
</div>
<div class="form-group">
	<label for="working_hours">Working hours</label>
	<input type="text" class="form-control form-control-sm" id="working_hours" name="working_hours" placeholder="Working hours">
</div>
<div class="form-group">
	<label for="phone">Phone number</label>
	<input type="text" class="form-control form-control-sm" id="phone" name="phone" placeholder="Phone number">
</div>
<div class="form-group">
	<label for="email">E-mail</label>
	<input type="text" class="form-control form-control-sm" id="email" name="email" placeholder="E-mail">
</div>
<div class="form-group">
	<label for="skype">Skype</label>
	<input type="text" class="form-control form-control-sm" id="skype" name="skype" placeholder="Skype">
</div>
<div class="form-group">
	<label for="whatsapp">WhatsApp</label>
	<input type="text" class="form-control form-control-sm" id="whatsapp" name="whatsapp" placeholder="WhatsApp">
</div>
<div class="form-group">
	<label for="map_link">Map link (Contacts)</label>
	<textarea class="form-control form-control-sm" id="map_link" name="map_link" rows="5"></textarea>
</div>
<div class="form-group">
	<label for="review_map_link">Map link (Reviews)</label>
	<textarea class="form-control form-control-sm" id="review_map_link" name="review_map_link" rows="5"></textarea>
</div>
<div class="form-group">
	<label for="scheme_file">Scheme file path</label>
	<div class="custom-file">
		<input type="file" class="custom-file-input" id="scheme_file" name="scheme_file">
		<label class="custom-file-label" for="scheme_file">Choose a file</label>
	</div>
</div>
<div class="form-group">
	<label for="is_active">Is active</label>
	<select class="form-control form-control-sm" id="is_active" name="is_active">
		<option value="1" selected>Yes</option>
		<option value="0">No</option>
	</select>
</div>
