<input type="hidden" id="id" name="id" value="{{ $city->id }}">
<div class="form-group">
	<label for="name">Name</label>
	<input type="text" class="form-control" id="name" name="name" value="{{ $city->name }}" placeholder="Name">
</div>
<div class="form-group">
	<label for="alias">Alias</label>
	<input type="text" class="form-control" id="alias" name="alias" value="{{ $city->alias }}" placeholder="Alias">
</div>
<div class="form-group">
	<label for="email">E-mail</label>
	<input type="email" class="form-control" id="email" name="email" value="{{ $city->email }}"placeholder="E-mail">
</div>
<div class="form-group">
	<label for="phone">Phone number</label>
	<input type="text" class="form-control" id="phone" name="phone" value="{{ $city->phone }}" placeholder="Phone number">
</div>
<div class="form-group">
	<label for="whatsapp">WhatsApp</label>
	<input type="text" class="form-control" id="whatsapp" name="whatsapp" value="{{ $city->whatsapp }}" placeholder="WhatsApp">
</div>
<div class="form-group">
	<label for="is_active">Is active</label>
	<select class="form-control" id="is_active" name="is_active">
		<option value="1" @if($city->is_active) selected @endif>Yes</option>
		<option value="0" @if(!$city->is_active) selected @endif>No</option>
	</select>
</div>
