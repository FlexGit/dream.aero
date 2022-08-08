<input type="hidden" id="id" name="id" value="{{ $paymentMethod->id }}">
<div class="form-group">
	<label for="name">Name</label>
	<input type="text" class="form-control" id="name" name="name" value="{{ $paymentMethod->name }}" placeholder="Name">
</div>
<div class="form-group">
	<label for="alias">Alias</label>
	<input type="text" class="form-control" id="alias" name="alias" value="{{ $paymentMethod->alias }}" placeholder="Alias" readonly>
</div>
<div class="form-group">
	<label for="is_active">Is active</label>
	<select class="form-control" id="is_active" name="is_active">
		<option value="1" @if($paymentMethod->is_active) selected @endif>Yes</option>
		<option value="0" @if(!$paymentMethod->is_active) selected @endif>No</option>
	</select>
</div>
