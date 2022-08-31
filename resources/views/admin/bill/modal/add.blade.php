<input type="hidden" id="deal_id" name="deal_id" value="{{ $deal->id }}">
<input type="hidden" id="amount" name="amount" value="{{ $amount }}">
<input type="hidden" id="tax" name="tax" value="{{ $tax }}">
<input type="hidden" id="tax_rate" name="tax_rate" value="{{ $taxRate }}">
<input type="hidden" id="total_amount" name="total_amount" value="{{ $totalAmount }}">
<input type="hidden" id="currency_id" name="currency_id" value="{{ $currency->id }}">

<div class="row">
	<div class="col-3">
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
	<div class="col-3">
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
	<div class="col-3">
		<div class="form-group">
			<label for="amount">Amount, {{ $currency->name }}</label>
			<input type="text" class="form-control text-right js-manual-amount" id="manual_amount" name="manual_amount" value="{{ $amount }}" placeholder="">
		</div>
	</div>
</div>
<div class="row">
	<div class="col text-right">
		<div class="form-group">
			<div id="amount-text" style="font-size: 30px;">
				Subtotal: <i class="fas fa-dollar-sign"></i> <span class="d-inline-block">{{ $amount }}</span>
			</div>
			<div id="tax-text" style="font-size: 18px;">
				Tax: <i class="fas fa-dollar-sign"></i> <span class="d-inline-block">{{ $tax }}</span>
			</div>
			<div id="total-amount-text" style="font-size: 18px;">
				Total: <i class="fas fa-dollar-sign"></i> <span class="d-inline-block">{{ $totalAmount }}</span>
			</div>
		</div>
	</div>
</div>