<div class="row">
	<div class="col">
		<div class="form-group">
			<label for="amount">Amount</label>
			<input type="text" class="form-control" id="amount" name="amount" placeholder="Amount">
		</div>
	</div>
	<div class="col">
		<div class="form-group">
			<label for="operated_at">Date</label>
			<input type="date" class="form-control" id="operated_at" name="operated_at">
		</div>
	</div>
</div>
<div class="row">
	<div class="col">
		<div class="form-group">
			<label for="type">Type</label>
			<select class="form-control" id="type" name="type">
				<option></option>
				@foreach($types as $k => $v)
					@if(!in_array($k, ['expense', 'refund']))
						@continue
					@endif
					<option value="{{ $k }}">{{ $v }}</option>
				@endforeach
			</select>
		</div>
	</div>
	<div class="col">
		<div class="form-group">
			<label for="payment_method_id">Payment method</label>
			<select class="form-control" id="payment_method_id" name="payment_method_id">
				<option></option>
				@foreach($paymentMethods as $paymentMethod)
					<option value="{{ $paymentMethod->id }}">{{ $paymentMethod->name }}</option>
				@endforeach
			</select>
		</div>
	</div>
</div>
<div class="row">
	<div class="col">
		<div class="form-group">
			<label for="comments">Comments</label>
			<textarea class="form-control" id="comments" name="comments"></textarea>
		</div>
	</div>
</div>
