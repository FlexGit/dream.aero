<table class="table table-sm table-bordered table-data">
	<tbody>
		<tr>
			<td>
				<table class="table table-sm table-striped">
					<tbody>
					<tr>
						<td class="align-top text-center font-weight-bold">Date</td>
						<td class="align-top text-center font-weight-bold">Type</td>
						<td class="align-top text-center font-weight-bold">Amount</td>
						<td class="align-top text-center font-weight-bold">Payment method</td>
					</tr>
					@if(count($items))
						@foreach($items as $date => $item)
							<tr>
								<td class="align-top text-center">
									{{ $date }}
								</td>
								<td class="align-top text-center">
									{{ $item['type'] }}
								</td>
								<td class="align-top text-right">
									{{ $item['currency'] }}{{ number_format($item['amount'], 2, '.', ' ') }}
								</td>
								<td class="align-top text-center">
									{{ $item['payment_method'] }}
								</td>
							</tr>
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
			</td>
		</tr>
	</tbody>
</table>
