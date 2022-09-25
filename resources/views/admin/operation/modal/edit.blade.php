<div class="row">
	<div class="col">
		<div class="form-group">
			<label for="amount">Amount</label>
			<input type="text" class="form-control" id="amount" name="amount" value="{{ $operation->amount }}" placeholder="Amount">
		</div>
	</div>
	<div class="col">
		<div class="form-group">
			<label for="received_at">Date</label>
			<input type="date" class="form-control" id="received_at" name="received_at" value="{{ $operation->operated_at->format('Y-m-d') }}">
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
					<option value="{{ $k }}" @if($operation->type == $k) selected @endif>{{ $v }}</option>
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
					<option value="{{ $paymentMethod->id }}" @if($operation->paymentMethod && $operation->paymentMethod->id == $paymentMethod->id) selected @endif>{{ $paymentMethod->name }}</option>
				@endforeach
			</select>
		</div>
	</div>
</div>
