@php
	$periodArr = explode('-', $period);
	$periodYear = $periodArr[0];
	$periodMonth = $periodArr[1];
@endphp
<table>
	<tbody>
	<tr>
		<td style="text-align: center;font-weight: bold;">Date</td>
		<td style="text-align: center;font-weight: bold;">Type</td>
		<td style="text-align: center;font-weight: bold;">Expenses</td>
		<td style="text-align: center;font-weight: bold;">Payment method</td>
		<td style="text-align: center;font-weight: bold;">Amount</td>
		<td style="text-align: center;font-weight: bold;">Extra</td>
	</tr>
	@if(isset($items[$periodYear . $periodMonth]))
		@foreach($items[$periodYear . $periodMonth] as $date => $dateItems)
			@foreach($dateItems as $dateItem)
				<tr>
					<td style="text-align: center;">
						{{ \Carbon\Carbon::parse($date)->format('m/d/Y') }}
					</td>
					<td style="text-align: center;">
						{{ $dateItem['type'] }}
					</td>
					<td style="text-align: center;">
						{{ $dateItem['expenses'] }}
					</td>
					<td style="text-align: center;">
						{{ $dateItem['payment_method'] }}
					</td>
					<td style="text-align: right;">
						{{ number_format($dateItem['amount'], 2, '.', '') }}
					</td>
					<td style="text-align: center;">
						{{ $dateItem['extra'] }}
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
