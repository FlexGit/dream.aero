<table class="table table-sm table-bordered table-striped" style="width: 500px;">
	<tbody>
	<tr>
		<td class="align-top text-center font-weight-bold"></td>
		<td class="align-top text-center font-weight-bold">{{ \Carbon\Carbon::parse($dateFromAtTimestamp)->format('m/d/Y') }}</td>
		<td class="align-top text-center font-weight-bold">{{ \Carbon\Carbon::parse($dateToAtTimestamp)->format('m/d/Y') }}</td>
	</tr>
	@foreach($paymentMethods as $paymentMethod)
		@if(!isset($balanceItems[$dateFromAtTimestamp][$paymentMethod->alias]))
			@continue
		@endif
		<tr>
			<td class="align-top text-center">{{ $paymentMethod->name }}</td>
			<td class="align-top text-right">{{ $currency }}{{ $balanceItems[$dateFromAtTimestamp][$paymentMethod->alias] }}</td>
			<td class="align-top text-right">{{ $currency }}{{ $balanceItems[$dateToAtTimestamp][$paymentMethod->alias] }}</td>
		</tr>
	@endforeach
	</tbody>
</table>

@foreach($periods as $period)
	@php
		$periodArr = explode('-', $period);
		$periodYear = $periodArr[0];
		$periodMonth = $periodArr[1];
	@endphp
	<h4>{{ $months[$periodMonth] . ' ' . $periodYear }}</h4>
	<table class="table table-sm table-bordered table-striped">
		<tbody>
		<tr>
			<td class="col-1 align-top text-center font-weight-bold">Date</td>
			<td class="col-1 align-top text-center font-weight-bold">Type</td>
			<td class="col-2 align-top text-center font-weight-bold">Payment method</td>
			<td class="col-2 align-top text-center font-weight-bold">Amount</td>
			<td class="col-6 align-top text-center font-weight-bold">Extra</td>
		</tr>
		@if(isset($items[$periodYear . $periodMonth]))
			@foreach($items[$periodYear . $periodMonth] as $date => $dateItems)
				@foreach($dateItems as $dateItem)
					<tr>
						<td class="align-top text-center">
							{{ \Carbon\Carbon::parse($date)->format('m/d/Y') }}
						</td>
						<td class="align-top text-center">
							{{ $dateItem['type'] }}
						</td>
						<td class="align-top text-center">
							{{ $dateItem['payment_method'] }}
						</td>
						<td class="align-top text-right">
							{{ $dateItem['currency'] }}{{ number_format($dateItem['amount'], 2, '.', ' ') }}
						</td>
						<td class="align-top text-center">
							{!! $dateItem['extra'] !!}
						</td>
					</tr>
				@endforeach
			@endforeach
		@else
			<tr>
				<td colspan="20" class="align-middle text-center">
					Nothing found
				</td>
			</tr>
		@endif
		</tbody>
	</table>
@endforeach