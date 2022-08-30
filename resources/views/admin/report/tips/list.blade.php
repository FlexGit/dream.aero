<table class="table table-sm table-bordered table-data">
	<tbody>
		<tr>
			<td>
				<table class="table table-sm table-striped">
					<tbody>
					@foreach($tipsItems ?? [] as $tipsItem)
						<tr>
							<td colspan="7" class="align-middle bg-gradient-olive font-weight-bold">
								{{ $tipsItem['fio'] }} <small>[ {{ $tipsItem['role'] }} ]</small>
							</td>
						</tr>
						@if(isset($billItems[$userItem['id']]))
							<tr>
								<td class="align-top text-center font-weight-bold">Invoice #</td>
								<td class="align-top text-center font-weight-bold">Invoice status</td>
								<td class="align-top text-center font-weight-bold">Invoice amount</td>
								<td class="align-top text-center font-weight-bold">Invoice paydate</td>
								<td class="align-top text-center font-weight-bold">Invoice location</td>
								<td class="align-top text-center font-weight-bold">Deal #</td>
								<td class="align-top text-center font-weight-bold">Deal status</td>
							</tr>
							@foreach($billItems[$userItem['id']] ?? [] as $billItem)
								<tr>
									<td class="align-top text-center">
										{{ $billItem['bill_number'] }}
									</td>
									<td class="align-top text-center">
										{{ $billItem['bill_status'] }}
									</td>
									<td class="align-top text-right">
										{{ $currencyName }}{{ number_format($billItem['bill_amount'], 0, '.', ' ') }}
									</td>
									<td class="align-top text-center">
										{{ $billItem['bill_payed_at'] }}
									</td>
									<td class="align-top text-center">
										{{ $billItem['bill_location'] }}
									</td>
									<td class="align-top text-center">
										{{ $billItem['deal_number'] }}
									</td>
									<td class="align-top text-center">
										{{ $billItem['deal_status'] }}
									</td>
								</tr>
							@endforeach
							<tr class="bg-gradient-yellow">
								<td class="align-top text-center font-weight-bold">Deal count</td>
								<td class="align-top text-center font-weight-bold">Deal amount</td>
								<td class="align-top text-center font-weight-bold">Invoice count</td>
								<td class="align-top text-center font-weight-bold">Invoices amount</td>
								<td class="align-top text-center font-weight-bold">Paid invoices count</td>
								<td class="align-top text-center font-weight-bold">Paid invoices amount</td>
								<td class="align-top text-center font-weight-bold">Shifts count</td>
							</tr>
							<tr>
								<td class="align-top text-right">
									{{ isset($totalItems[$userItem['id']]) ? $totalItems[$userItem['id']]['deal_count'] : 0 }}
								</td>
								<td class="align-top text-right">
									{{ $currencyName }}{{ isset($totalItems[$userItem['id']]) ? number_format($totalItems[$userItem['id']]['deal_sum'], 0, '.', ' ') : 0 }}
								</td>
								<td class="align-top text-right">
									{{ isset($totalItems[$userItem['id']]) ? $totalItems[$userItem['id']]['bill_count'] : 0 }}
								</td>
								<td class="align-top text-right">
									{{ $currencyName }}{{ isset($totalItems[$userItem['id']]) ? number_format($totalItems[$userItem['id']]['bill_sum'], 0, '.', ' ') : 0 }}
								</td>
								<td class="align-top text-right">
									{{ isset($totalItems[$userItem['id']]) ? $totalItems[$userItem['id']]['payed_bill_count'] : 0 }}
								</td>
								<td class="align-top text-right">
									{{ $currencyName }}{{ isset($totalItems[$userItem['id']]) ? number_format($totalItems[$userItem['id']]['payed_bill_sum'], 0, '.', ' ') : 0 }}
								</td>
								<td class="align-top text-right">
									{{ isset($shiftItems[$userItem['id']]) ? $shiftItems[$userItem['id']] : 0 }}
								</td>
							</tr>
						@else
							<tr>
								<td colspan="7" class="align-middle text-center">
									Nothing found
								</td>
							</tr>
						@endif
					@endforeach
					</tbody>
				</table>
			</td>
		</tr>
	</tbody>
</table>
