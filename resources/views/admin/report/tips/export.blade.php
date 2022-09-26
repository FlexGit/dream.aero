<table>
	<tbody>
	<tr>
		<td style="text-align: center;font-weight: bold;">Employee</td>
		<td style="text-align: center;font-weight: bold;">Role</td>
		<td style="text-align: center;font-weight: bold;">Amount</td>
	</tr>
	@foreach($items ?? [] as $userId => $amount)
		<tr>
			<td style="text-align: center;">{{ isset($userItems[$userId]['fio']) ? $userItems[$userId]['fio'] : '' }}</td>
			<td style="text-align: center;">{{ isset($userItems[$userId]['role']) ? $userItems[$userId]['role'] : '' }}</td>
			<td style="text-align: right;">{{ number_format($amount, 2, '.', '') }}</td>
		</tr>
	@endforeach
	</tbody>
</table>
