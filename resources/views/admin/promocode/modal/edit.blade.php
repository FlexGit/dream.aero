<input type="hidden" id="id" name="id" value="{{ $promocode->id }}">
<div class="form-group">
	<label for="number">Number</label>
	<input type="text" class="form-control" id="number" name="number" value="{{ $promocode->number }}" placeholder="Number">
</div>
<div class="row">
	<div class="col">
		<div class="form-group">
			<label for="discount_id">Discount</label>
			<select class="form-control" id="discount_id" name="discount_id">
				<option value=""></option>
				@foreach($discounts ?? [] as $discount)
					<option value="{{ $discount->id }}" @if($discount->id == $promocode->discount_id) selected @endif>{{ $discount->valueFormatted() }}</option>
				@endforeach
			</select>
		</div>
	</div>
	<div class="col">
		<div class="form-group">
			<label for="is_active">Is active</label>
			<select class="form-control" id="is_active" name="is_active">
				<option value="1" @if($promocode->is_active) selected @endif>Yes</option>
				<option value="0" @if(!$promocode->is_active) selected @endif>No</option>
			</select>
		</div>
	</div>
</div>
<div class="form-group">
	<label for="flight_at">Activity start date</label>
	<div class="d-flex">
		<input type="date" class="form-control" id="active_from_at_date" name="active_from_at_date" value="{{ $promocode->active_from_at ? Carbon\Carbon::parse($promocode->active_from_at)->format('Y-m-d') : '' }}">
		<input type="time" class="form-control ml-2" id="active_from_at_time" name="active_from_at_time" value="{{ $promocode->active_from_at ? Carbon\Carbon::parse($promocode->active_from_at)->format('H:i') : '' }}">
	</div>
</div>
<div class="form-group">
	<label for="flight_at">Activity end date</label>
	<div class="d-flex">
		<input type="date" class="form-control" id="active_to_at_date" name="active_to_at_date" value="{{ $promocode->active_to_at ? Carbon\Carbon::parse($promocode->active_to_at)->format('Y-m-d') : '' }}">
		<input type="time" class="form-control ml-2" id="active_to_at_time" name="active_to_at_time" value="{{ $promocode->active_to_at ? Carbon\Carbon::parse($promocode->active_to_at)->format('H:i') : '' }}">
	</div>
</div>
