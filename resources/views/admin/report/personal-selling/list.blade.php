<div class="row flex justify-content-between">
	<div class="col-3">
		<table class="table table-sm table-bordered table-striped table-data">
			<thead>
			<tr>
				<th>
					All paid invoices amount
				</th>
			</tr>
			</thead>
			<tbody>
			<tr>
				<td class="text-right">
				{{ $currencyName }}{{ number_format($totalSum, 2, '.', ' ') }}
				</td>
			</tr>
			</tbody>
		</table>
	</div>
	<div class="col-4">
		<table class="table table-sm table-bordered table-striped table-data">
			<thead>
				<tr>
					<th>
						Payment method
					</th>
					<th>
						Paid invoices amount
					</th>
				</tr>
			</thead>
			<tbody>
			@foreach($paymentMethods as $paymentMethod)
				<tr>
					<td class="text-center">
						{{ $paymentMethod->name }}
					</td>
					<td class="text-right">
						{{ $currencyName }}{{ isset($paymentMethodSumItems[$paymentMethod->id]) ? number_format($paymentMethodSumItems[$paymentMethod->id], 2, '.', ' ') : 0 }}
					</td>
				</tr>
			@endforeach
			@if(isset($paymentMethodSumItems[0]))
				<tr>
					<td>
						-
					</td>
					<td class="text-right">
						{{ $currencyName }}{{ number_format($paymentMethodSumItems[0], 2, '.', ' ') }}
					</td>
				</tr>
			@endif
			</tbody>
		</table>
	</div>
</div>


<table class="table table-sm table-bordered table-data">
	<tbody>
		<tr>
			<td>
				<table class="table table-sm table-striped">
					<tbody>
					@foreach($userItems ?? [] as $userItem)
						<tr>
							<td colspan="7" class="align-middle bg-gradient-olive font-weight-bold">
								{{ $userItem['fio'] }} <small>[ {{ $userItem['role'] }} ]</small>
							</td>
						</tr>
						@if(isset($billItems[$userItem['id']]))
							<tr>
								<td class="align-top text-center font-weight-bold">Invoice #</td>
								<td class="align-top text-center font-weight-bold">Invoice status</td>
								<td class="align-top text-center font-weight-bold">Invoice amount</td>
								<td class="align-top text-center font-weight-bold">Invoice paydate</td>
								<td class="align-top text-center font-weight-bold">Invoice payment method</td>
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
										{{ $currencyName }}{{ number_format($billItem['bill_amount'], 2, '.', ' ') }}
									</td>
									<td class="align-top text-center">
										{{ $billItem['bill_payed_at'] }}
									</td>
									<td class="align-top text-center">
										{{ $billItem['bill_payment_method'] }}
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
									{{ $currencyName }}{{ isset($totalItems[$userItem['id']]) ? number_format($totalItems[$userItem['id']]['deal_sum'], 2, '.', ' ') : 0 }}
								</td>
								<td class="align-top text-right">
									{{ isset($totalItems[$userItem['id']]) ? $totalItems[$userItem['id']]['bill_count'] : 0 }}
								</td>
								<td class="align-top text-right">
									{{ $currencyName }}{{ isset($totalItems[$userItem['id']]) ? number_format($totalItems[$userItem['id']]['bill_sum'], 2, '.', ' ') : 0 }}
								</td>
								<td class="align-top text-right">
									{{ isset($totalItems[$userItem['id']]) ? $totalItems[$userItem['id']]['payed_bill_count'] : 0 }}
								</td>
								<td class="align-top text-right">
									{{ $currencyName }}{{ isset($totalItems[$userItem['id']]) ? number_format($totalItems[$userItem['id']]['payed_bill_sum'], 2, '.', ' ') : 0 }}
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
