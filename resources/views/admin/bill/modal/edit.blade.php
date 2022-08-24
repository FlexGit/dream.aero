<input type="hidden" id="id" name="id" value="{{ $bill->id }}">

<div class="row">
	<div class="col-3">
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
	<div class="col-3">
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
					<small>{{ \Carbon\Carbon::parse($bill->payed_at)->format('m/d/Y g:i A') }}</small>
				</div>
			@endif
		</div>
	</div>
	<div class="col-6 text-right">
		<div class="form-group">
			<div id="amount-text" style="font-size: 30px;">
				Subtotal: <i class="fas fa-dollar-sign"></i> <span class="d-inline-block">{{ $bill->amount }}</span>
			</div>
			<div id="tax-text" style="font-size: 18px;">
				Tax: <i class="fas fa-dollar-sign"></i> <span class="d-inline-block">{{ $bill->tax }}</span>
			</div>
			<div id="total-amount-text" style="font-size: 18px;">
				Total: <i class="fas fa-dollar-sign"></i> <span class="d-inline-block">{{ $bill->total_amount }}</span>
			</div>
		</div>
	</div>
</div>
<div class="row">
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
							{{ \Carbon\Carbon::parse($bill->link_sent_at)->format('m/d/Y g:i A') }}
						</div>
					@endif
					@if($bill->success_payment_sent_at)
						<div>
							payment notification sent:<br>
							{{ \Carbon\Carbon::parse($bill->success_payment_sent_at)->format('m/d/Y g:i A') }}
						</div>
					@endif
				</div>
			@endif
		@endif
	</div>
</div>
<div class="row">
	<div class="col">
		@if(isset($bill->data_json['payment']))
			<div>
				<label>Payment details</label>
			</div>
			<div>
				@foreach($bill->data_json['payment'] ?? [] as $key => $value)
					@if($loop->index != 0)
						|
					@endif
					{{ $key . ': ' . $value }}
				@endforeach
			</div>
		@endif
	</div>
</div>
