<input type="hidden" id="deal_id" name="deal_id" value="{{ $deal->id }}">
<input type="hidden" id="currency_id" name="currency_id" value="1">

<div class="row">
	<div class="col">
		<div class="form-group">
			<label for="number">Number</label>
			<input type="text" class="form-control" id="number" name="number" placeholder="Number" disabled>
		</div>
	</div>
	<div class="col">
		<div class="form-group">
			<label for="status_id">Status</label>
			<select class="form-control" id="status_id" name="status_id">
				<option value=""></option>
				@foreach($statuses ?? [] as $status)
					<option value="{{ $status->id }}" @if($status->alias == app('\App\Models\Bill')::NOT_PAYED_STATUS) selected @endif>{{ $status->name }}</option>
				@endforeach
			</select>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-6">
		<div class="form-group">
			<label for="payment_method_id">Payment method</label>
			<select class="form-control" id="payment_method_id" name="payment_method_id">
				<option value=""></option>
				@foreach($paymentMethods ?? [] as $paymentMethod)
					<option value="{{ $paymentMethod->id }}" data-alias="{{ $paymentMethod->alias }}">{{ $paymentMethod->name }}</option>
				@endforeach
			</select>
		</div>
	</div>
	<div class="col-6">
		<div class="form-group">
			<label for="amount">Amount</label>
			<input type="number" class="form-control" id="amount" name="amount" value="{{ $amount }}" placeholder="Amount">
		</div>
	</div>
	{{--<div class="col-3">
		<div class="form-group">
			<label for="currency_id">Валюта</label>
			<select class="form-control" id="currency_id" name="currency_id">
				@foreach($currencies ?? [] as $currency)
					<option value="{{ $currency->id }}">{{ $currency->name }}</option>
				@endforeach
			</select>
		</div>
	</div>--}}
</div>
{{--<div class="row">
	<div class="col-6">
		<div class="form-group">
			<label for="position_id">Position</label>
			<select class="form-control" id="position_id" name="position_id">
				<option value=""></option>
				@foreach($positions as $position)
					<option value="{{ $position->id }}">{{ $position->number }}</option>
				@endforeach
			</select>
		</div>
	</div>
</div>--}}
