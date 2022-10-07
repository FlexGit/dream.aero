<input type="hidden" id="id" name="id" value="{{ $promo->id }}">

<div class="form-group">
	<label for="name">Name</label>
	<input type="text" class="form-control" id="name" name="name" value="{{ $promo->name }}" placeholder="Name">
</div>
<div class="form-group">
	<label for="alias">Alias</label>
	<input type="text" class="form-control" id="alias" name="alias" value="{{ $promo->alias }}" placeholder="Alias" readonly>
</div>
<div class="row">
	<div class="col">
		<div class="form-group">
			<label for="discount_id">Discount</label>
			<select class="form-control" id="discount_id" name="discount_id">
				<option></option>
				@foreach($discounts ?? [] as $discount)
					<option value="{{ $discount->id }}" @if($discount->id == $promo->discount_id) selected @endif>{{ $discount->valueFormatted() }}</option>
				@endforeach
			</select>
		</div>
	</div>
	<div class="col">
		<div class="form-group">
			<label for="is_published">For publication</label>
			<select class="form-control" id="is_published" name="is_published">
				<option value="1" @if($promo->is_published) selected @endif>Yes</option>
				<option value="0" @if(!$promo->is_published) selected @endif>No</option>
			</select>
		</div>
	</div>
	<div class="col">
		<div class="form-group">
			<label for="is_active">Is active</label>
			<select class="form-control" id="is_active" name="is_active">
				<option value="1" @if($promo->is_active) selected @endif>Yes</option>
				<option value="0" @if(!$promo->is_active) selected @endif>No</option>
			</select>
		</div>
	</div>
</div>
<div class="row">
	<div class="col">
		<div class="form-group">
			<label for="active_from_at">Activity start date</label>
			<input type="date" class="form-control" id="active_from_at" name="active_from_at" value="{{ $promo->active_from_at ? \Carbon\Carbon::parse($promo->active_from_at)->format('Y-m-d') : '' }}" placeholder="Activity start date">
		</div>
	</div>
	<div class="col">
		<div class="form-group">
			<label for="active_to_at">Activity end date</label>
			<input type="date" class="form-control" id="active_to_at" name="active_to_at" value="{{ $promo->active_to_at ? \Carbon\Carbon::parse($promo->active_to_at)->format('Y-m-d') : '' }}" placeholder="Activity end date">
		</div>
	</div>
</div>
