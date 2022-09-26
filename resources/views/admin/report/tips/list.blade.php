<table class="table table-sm table-bordered table-data" style="width: 300px;">
	<tbody>
	<tr>
		<td class="align-top text-center font-weight-bold">Employee</td>
		<td class="align-top text-center font-weight-bold">Role</td>
		<td class="align-top text-center font-weight-bold">Amount</td>
	</tr>
	@foreach($items ?? [] as $userId => $amount)
		<tr>
			<td class="align-top text-center">{{ isset($userItems[$userId]['fio']) ? $userItems[$userId]['fio'] : '' }}</td>
			<td class="align-top text-center">{{ isset($userItems[$userId]['role']) ? $userItems[$userId]['role'] : '' }}</td>
			<td class="align-top text-right">{{ $currency ? $currency->name : '' }}{{ number_format($amount, 2, '.', ' ') }}</td>
		</tr>
	@endforeach
	</tbody>
</table>
