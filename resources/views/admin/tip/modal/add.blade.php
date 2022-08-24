<div class="row">
	<div class="col">
		<div class="form-group">
			<label for="amount">Amount</label>
			<input type="text" class="form-control" id="amount" name="amount" placeholder="Amount">
		</div>
	</div>
	<div class="col">
		<div class="form-group">
			<label for="received_at">Receiving date</label>
			<input type="date" class="form-control" id="received_at" name="received_at" placeholder="Receiving date">
		</div>
	</div>
</div>
<div class="row">
	<div class="col">
		<div class="form-group">
			<label for="source">Source</label>
			<select class="form-control" id="source" name="source">
				<option></option>
				@foreach($sources ?? [] as $sourceAlias => $sourceName)
					<option value="{{ $sourceAlias }}">{{ $sourceName }}</option>
				@endforeach
			</select>
		</div>
	</div>
	<div class="col">
		<div class="form-group">
			<label for="deal_number">Deal #</label>
			<input type="text" class="form-control" id="deal_number" name="deal_number" placeholder="Deal #">
		</div>
	</div>
</div>
