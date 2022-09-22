<table>
	<tbody>
		<tr>
			<td style="text-align: center;font-weight: bold;">
				All paid invoices amount
			</td>
		</tr>
		<tr>
			<td style="text-align: right;">
				{{ $totalSum }}
			</td>
		</tr>
	</tbody>
</table>

<br>

<table>
	<tbody>
		<tr>
			<td style="text-align: center;font-weight: bold;">
				Payment method
			</td>
			<td style="text-align: center;font-weight: bold;">
				Paid invoices amount
			</td>
		</tr>
		@foreach($paymentMethods as $paymentMethod)
			<tr>
				<td>
					{{ $paymentMethod->name }}
				</td>
				<td style="text-align: right;">
					{{ isset($paymentMethodSumItems[$paymentMethod->id]) ? $paymentMethodSumItems[$paymentMethod->id] : 0 }}
				</td>
			</tr>
		@endforeach
	</tbody>
</table>

<br>

<table>
	<tbody>
	@foreach($userItems ?? [] as $userItem)
		<tr>
			<td colspan="7" style="background-color: #3d9970;color: #ffffff;font-weight: bold;">
				{{ $userItem['fio'] }} [{{ $userItem['city_name'] ? $userItem['city_name'] . ', ' : '' }}{{ $userItem['role'] }}]
			</td>
		</tr>
		@if(isset($billItems[$userItem['id']]))
			<tr>
				<td style="text-align: center;font-weight: bold;">Invoice #</td>
				<td style="text-align: center;font-weight: bold;">Invoice status</td>
				<td style="text-align: center;font-weight: bold;">Invoice amount</td>
				<td style="text-align: center;font-weight: bold;">Invoice paydate</td>
				<td style="text-align: center;font-weight: bold;">Invoice location</td>
				<td style="text-align: center;font-weight: bold;">Deal #</td>
				<td style="text-align: center;font-weight: bold;">Deal status</td>
			</tr>
			@foreach($billItems[$userItem['id']] ?? [] as $billItem)
				<tr>
					<td style="text-align: center;">
						{{ $billItem['bill_number'] }}
					</td>
					<td style="text-align: center;">
						{{ $billItem['bill_status'] }}
					</td>
					<td style="text-align: right;">
						{{ $billItem['bill_amount'] }}
					</td>
					<td style="text-align: center;">
						{{ $billItem['bill_payed_at'] }}
					</td>
					<td style="text-align: center;">
						{{ $billItem['bill_location'] }}
					</td>
					<td style="text-align: center;">
						{{ $billItem['deal_number'] }}
					</td>
					<td style="text-align: center;">
						{{ $billItem['deal_status'] }}
					</td>
				</tr>
			@endforeach
			<tr>
				<td style="background-color: #ffc107;text-align: center;font-weight: bold;">Deal count</td>
				<td style="background-color: #ffc107;text-align: center;font-weight: bold;">Deal amount</td>
				<td style="background-color: #ffc107;text-align: center;font-weight: bold;">Invoice count</td>
				<td style="background-color: #ffc107;text-align: center;font-weight: bold;">Invoices amount</td>
				<td style="background-color: #ffc107;text-align: center;font-weight: bold;">Paid invoices count</td>
				<td style="background-color: #ffc107;text-align: center;font-weight: bold;">Paid invoices amount</td>
				<td style="background-color: #ffc107;text-align: center;font-weight: bold;">Shifts count</td>
			</tr>
			<tr>
				<td style="text-align: right;">
					{{ isset($totalItems[$userItem['id']]) ? $totalItems[$userItem['id']]['deal_count'] : 0 }}
				</td>
				<td style="text-align: right;">
					{{ isset($totalItems[$userItem['id']]) ? $totalItems[$userItem['id']]['deal_sum'] : 0 }}
				</td>
				<td style="text-align: right;">
					{{ isset($totalItems[$userItem['id']]) ? $totalItems[$userItem['id']]['bill_count'] : 0 }}
				</td>
				<td style="text-align: right;">
					{{ isset($totalItems[$userItem['id']]) ? $totalItems[$userItem['id']]['bill_sum'] : 0 }}
				</td>
				<td style="text-align: right;">
					{{ isset($totalItems[$userItem['id']]) ? $totalItems[$userItem['id']]['payed_bill_count'] : 0 }}
				</td>
				<td style="text-align: right;">
					{{ isset($totalItems[$userItem['id']]) ? $totalItems[$userItem['id']]['payed_bill_sum'] : 0 }}
				</td>
				<td style="text-align: right;">
					{{ isset($shiftItems[$userItem['id']]) ? $shiftItems[$userItem['id']] : 0 }}
				</td>
			</tr>
		@else
			<tr>
				<td colspan="7" style="text-align: center;">
					Nothing found
				</td>
			</tr>
		@endif
	@endforeach
	</tbody>
</table>
