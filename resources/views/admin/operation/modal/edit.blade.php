<div class="row">
	<div class="col">
		<div class="form-group">
			<label for="amount">Amount</label>
			<input type="text" class="form-control" id="amount" name="amount" value="{{ $operation->amount }}" placeholder="Amount">
		</div>
	</div>
	<div class="col">
		<div class="form-group">
			<label for="operated_at">Date</label>
			<input type="date" class="form-control" id="operated_at" name="operated_at" value="{{ $operation->operated_at->format('Y-m-d') }}">
		</div>
	</div>
</div>
<div class="row">
	<div class="col">
		<div class="form-group">
			<label for="operation_type_id">Type</label>
			<select class="form-control" id="operation_type_id" name="operation_type_id">
				<option></option>
				@foreach($types as $type)
					<option value="{{ $type->id }}">{{ $type->name }}</option>
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
<div class="row">
	<div class="col">
		<div class="form-group">
			<label for="comment">Comment</label>
			<textarea class="form-control" id="comment" name="comment">{{ $operation->data_json['comment'] }}</textarea>
		</div>
	</div>
</div>
