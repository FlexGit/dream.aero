<table>
	<tbody>
	<tr>
		<td></td>
		<td style="text-align: center;font-weight: bold;">{{ \Carbon\Carbon::parse($dateFromAtTimestamp)->format('m/d/Y') }}</td>
		<td style="text-align: center;font-weight: bold;">{{ \Carbon\Carbon::parse($dateToAtTimestamp)->format('m/d/Y') }}</td>
	</tr>
	@foreach($paymentMethods as $paymentMethod)
		@if(!isset($balanceItems[$dateFromAtTimestamp][$paymentMethod->alias]))
			@continue
		@endif
		<tr>
			<td style="text-align: center;">{{ $paymentMethod->name }}</td>
			<td style="text-align: right;">{{ number_format($balanceItems[$dateFromAtTimestamp][$paymentMethod->alias], 2, '.', '') }}</td>
			<td style="text-align: right;">{{ number_format($balanceItems[$dateToAtTimestamp][$paymentMethod->alias], 2, '.', '') }}</td>
		</tr>
	@endforeach
	</tbody>
</table>
