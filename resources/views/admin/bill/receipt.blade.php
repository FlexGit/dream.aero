@extends('admin.layouts.receipt')

@section('title')
	{{ 'Invoice #' . $bill->number . ' Receipt' }}
@stop

@section('content')
	<div style="text-align: center;">
		<div>
			<div>
				<span style="font-size: 14px;font-weight: bold;">Dream Aero DC</span>
			</div>
			<div>
				7101 Democracy Boulevard
				<br>
				Dream Aero store
				<br>
				Bethesda, Maryland,	20817
				<br>
				United States
				<br>
				(240) 224-4885
			</div>
		</div>
		<div style="margin-top: 10px;">
			<span style="font-size: 14px;font-weight: bold;">Sales Receipt</span>
		</div>
		<div>
			<small>{{ \Carbon\Carbon::now()->format('m/d/Y g:i A') }}</small>
		</div>
	</div>
	<div style="margin-top: 10px;">
		Ticket: {{ $bill->number }}
	</div>
	<div>
		Employee: {{ $user->name }}
	</div>

	<table cellspacing="0" cellpadding="0" style="margin-top: 20px;width: 100%;">
		<tr>
			<td style="height: 25px;border-bottom: 1pt solid #000;font-weight: bold;">Items</td>
			<td style="border-bottom: 1pt solid #000;font-weight: bold;text-align: right;">#</td>
			<td style="border-bottom: 1pt solid #000;font-weight: bold;text-align: right;">Price</td>
		</tr>
		<tr>
			<td style="width: 173px;height: 25px;border-bottom: 1pt solid #000;font-weight: bold;white-space: nowrap;">
				{{ $productName }}
			</td>
			<td style="border-bottom: 1pt solid #000;text-align: right;">
				1
			</td>
			<td style="border-bottom: 1pt solid #000;text-align: right;">
				{{ $currencyName }}{{ number_format($bill->amount, 2, '.', ' ') }}
			</td>
		</tr>
	</table>
	<table cellspacing="0" cellpadding="0" style="width: 100%;">
		<tr>
			<td style="width: 200px;padding-top: 3px;text-align: right;">
				Subtotal
			</td>
			<td style="text-align: right;">
				{{ $currencyName }}{{ number_format($bill->amount, 2, '.', ' ') }}
			</td>
		</tr>
		<tr>
			<td style="text-align: right;white-space: nowrap;">
				Tax ({{ $currencyName }}{{ number_format($bill->amount, 2, '.', ' ') }} @ {{ $taxRate }}%)
			</td>
			<td style="text-align: right;">
				{{ $currencyName }}{{ number_format($bill->tax, 2, '.', ' ') }}
			</td>
		</tr>
		<tr>
			<td style="text-align: right;">Total Tax</td>
			<td style="text-align: right;">{{ $currencyName }}{{ number_format($bill->tax, 2, '.', ' ') }}</td>
		</tr>
		<tr>
			<td style="text-align: right;font-weight: bold;">Total</td>
			<td style="text-align: right;font-weight: bold;">{{ $currencyName }}{{ number_format($bill->total_amount, 2, '.', ' ') }}</td>
		</tr>
	</table>
	<table cellspacing="0" cellpadding="0" style="margin-top: 10px;width: 100%;">
		<tr>
			<td colspan="2" style="height: 25px;border-bottom: 1pt solid #000;font-size: 14px;font-weight: bold;">Payments</td>
		</tr>
		<tr>
			<td style="width: 200px;padding-top: 3px;text-align: right;">{{ $paymentMethodName }}</td>
			<td style="text-align: right;">{{ $currencyName }}{{ number_format($bill->total_amount, 2, '.', ' ') }}</td>
		</tr>
	</table>

	<div style="margin-top: 20px;text-align: center;">
		Thank You !
	</div>
@stop

@section('js')
	<script>
		window.print();
	</script>
@stop