<input type="hidden" id="id" name="id" value="{{ $bill->id }}">
<input type="hidden" id="currency_id" name="currency_id" value="1">

<div class="row">
	<div class="col">
		<div class="form-group">
			<label for="number">Number</label>
			<input type="text" class="form-control" id="number" name="number" value="{{ $bill->number }}" placeholder="Number" disabled>
		</div>
	</div>
	<div class="col">
		<div class="form-group">
			<label for="status_id">Status</label>
			<select class="form-control" id="status_id" name="status_id">
				<option value=""></option>
				@foreach($statuses ?? [] as $status)
					<option value="{{ $status->id }}" @if($status->id == $bill->status_id) selected @endif>{{ $status->name }}</option>
				@endforeach
			</select>
			@if($bill->payed_at)
				<div>
					<small>Payment date: {{ \Carbon\Carbon::parse($bill->payed_at)->format('Y-m-d H:i:s') }}</small>
				</div>
			@endif
		</div>
	</div>
</div>
<div class="row">
	<div class="col">
		<div class="form-group">
			<label for="payment_method_id">Payment method</label>
			<select class="form-control" id="payment_method_id" name="payment_method_id">
				<option value=""></option>
				@foreach($paymentMethods ?? [] as $paymentMethod)
					<option value="{{ $paymentMethod->id }}" data-alias="{{ $paymentMethod->alias }}" @if($paymentMethod->id == $bill->payment_method_id) selected @endif>{{ $paymentMethod->name }}</option>
				@endforeach
			</select>
		</div>
	</div>
	<div class="col">
		<div class="form-group">
			<label for="amount">Amount</label>
			<input type="number" class="form-control" id="amount" name="amount" value="{{ $bill->amount }}" placeholder="Amount">
		</div>
	</div>
</div>
<div class="row">
	<div class="col">
		<div class="form-group">
			<label for="position_id">Item</label>
			<select class="form-control" id="position_id" name="position_id">
				<option value=""></option>
				@foreach($positions as $position)
					<option value="{{ $position->id }}" @if($position->id == $bill->deal_position_id) selected @endif>{{ $position->number }}</option>
				@endforeach
			</select>
		</div>
	</div>
	<div class="col">
		@if ($bill->paymentMethod)
			@if ($bill->paymentMethod->alias == app('\App\Models\PaymentMethod')::ONLINE_ALIAS)
				<div class="form-group">
					<label>Payment link</label>
					<div>
						[ <a href="{{ url('//' . env('DOMAIN_SITE')) . '/payment/' . $bill->uuid }}" target="_blank">open</a> ]
					</div>
					@if($bill->link_sent_at)
						<div>
							payment link sent:<br>
							{{ \Carbon\Carbon::parse($bill->link_sent_at)->format('Y-m-d H:i:s') }}
						</div>
					@endif
					@if($bill->success_payment_sent_at)
						<div>
							payment notification sent:<br>
							{{ \Carbon\Carbon::parse($bill->success_payment_sent_at)->format('Y-m-d H:i:s') }}
						</div>
					@endif
				</div>
			@endif
		@endif
	</div>
</div>
