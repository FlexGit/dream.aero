<table>
	<tbody>
	<tr>
		<td></td>
		<td style="text-align: center;font-weight: bold;">{{ \Carbon\Carbon::parse($dateFromAt)->format('m/d/Y g:i A') }}</td>
		<td style="text-align: center;font-weight: bold;">{{ \Carbon\Carbon::parse($dateToAt)->format('m/d/Y g:i A') }}</td>
	</tr>
	@foreach($paymentMethods as $paymentMethod)
		@if(!isset($balanceItems[$dateFromAt][$paymentMethod->alias]))
			@continue
		@endif
		<tr>
			<td style="text-align: center;">{{ $paymentMethod->name }}</td>
			<td style="text-align: right;">{{ number_format($balanceItems[$dateFromAt][$paymentMethod->alias], 2, '.', '') }}</td>
			<td style="text-align: right;">{{ number_format($balanceItems[$dateToAt][$paymentMethod->alias], 2, '.', '') }}</td>
		</tr>
	@endforeach
	</tbody>
</table>
