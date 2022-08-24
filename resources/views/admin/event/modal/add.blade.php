<input type="hidden" id="deal_id" name="deal_id" value="{{ $deal->id }}">
<input type="hidden" id="source" name="source" value="{{ app('\App\Models\Event')::EVENT_SOURCE_DEAL }}">

<div class="row">
	<div class="col">
		<div class="form-group">
			<label>Flight start</label>
			<div class="d-flex">
				<input type="date" class="form-control" name="start_at_date" placeholder="">
				<input type="time" class="form-control ml-2" name="start_at_time" placeholder="">
			</div>
		</div>
	</div>
	<div class="col">
		<div class="form-group">
			<label for="extra_time">Extra time</label>
			<select class="form-control" id="extra_time" name="extra_time">
				<option value="0"></option>
				<option value="15">15 min</option>
			</select>
		</div>
	</div>
</div>
<div class="row">
	<div class="col">
		<div class="form-group">
			<label for="is_repeated_flight">Repeated</label>
			<select class="form-control" id="is_repeated_flight" name="is_repeated_flight">
				<option value="0" selected>No</option>
				<option value="1">Yes</option>
			</select>
		</div>
	</div>
	<div class="col">
		<div class="form-group">
			<label for="is_unexpected_flight">Spontaneous</label>
			<select class="form-control" id="is_unexpected_flight" name="is_unexpected_flight">
				<option value="0" selected>No</option>
				<option value="1">Yes</option>
			</select>
		</div>
	</div>
</div>

