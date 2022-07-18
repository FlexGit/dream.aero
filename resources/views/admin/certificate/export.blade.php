<table id="certificateTable" class="table table-hover table-sm table-bordered table-striped table-data">
	<thead>
	<tr>
		<th class="ext-center align-middle">Voucher #</th>
		<th class="align-middle">Voucher date</th>
		<th class="align-middle">Product</th>
		<th class="align-middle">Amount, {{ ($city->alias == 'uae') ? 'AED' : 'USD' }}</th>
		<th class="align-middle">Voucher status</th>
		<th class="align-middle">Voucher validity</th>
		<th class="align-middle">Invoice #</th>
		<th class="align-middle">Invoice status</th>
		<th class="align-middle">Payment method</th>
		<th class="align-middle">Comment</th>
	</tr>
	</thead>
	<tbody class="body">
	@if(count($certificateItems))
		@foreach($certificateItems ?? [] as $certificateId => $certificateItem)
			<tr class="odd" data-id="{{ $certificateId }}">
				<td class="align-middle text-center">
					{{ $certificateItem['number'] }}
				</td>
				<td class="align-middle text-center">
					{{ $certificateItem['created_at'] }}
				</td>
				<td class="align-middle text-center">
					{{ $certificateItem['certificate_product_name'] }}
					@if($certificateItem['certificate_product_name'] != $certificateItem['position_product_name'])
						<br>
						Product was changed to {{ $certificateItem['position_product_name'] }}
					@endif
				</td>
				<td class="align-middle text-right">
					{{ number_format($certificateItem['position_amount'], 2, '.', ' ') }}
				</td>
				<td class="align-middle text-center">
					{{ $certificateItem['certificate_status_name'] }}
				</td>
				<td class="align-middle text-center">
					{{ $certificateItem['expire_at'] }}
				</td>
				<td class="align-middle text-center">
					@if($certificateItem['bill_number'])
						{{ $certificateItem['bill_number'] }}
					@endif
				</td>
				<td class="align-middle text-center text-nowrap">
					@if($certificateItem['bill_number'])
						@if($certificateItem['bill_status_alias'] == app('\App\Models\Bill')::PAYED_STATUS)
							<span class="pl-2 pr-2" style="background-color: #e9ffc9;">{{ $certificateItem['bill_status_name'] }}</span>
						@else
							<span class="pl-2 pr-2" style="background-color: #ffbdba;">{{ $certificateItem['bill_status_name'] }}</span>
						@endif
					@endif
				</td>
				<td class="align-middle text-center">
					@if($certificateItem['bill_number'])
						{{ $certificateItem['bill_payment_method_name'] }}
					@endif
				</td>
				<td class="align-middle text-left">
					@if($certificateItem['comment'] /*|| $certificateItem['certificate_whom'] || $certificateItem['certificate_whom_phone']*/)
						<div style="border: 1px solid;border-radius: 6px;padding: 4px 8px;background-color: #fff;">
							@if($certificateItem['comment'])
								<div>
									<i class="far fa-comment-dots"></i>&nbsp;
									<span><i>{{ $certificateItem['comment'] }}</i></span>
								</div>
							@endif
							{{--@if($certificateItem['certificate_whom'])
								<div>
									<i class="fas fa-user"></i>&nbsp;
									<span><i>{{ $certificateItem['certificate_whom']}}</i></span>
								</div>
							@endif
							@if($certificateItem['certificate_whom_phone'])
								<div>
									<i class="fas fa-mobile-alt"></i>&nbsp;
									<span><i>{{ $certificateItem['certificate_whom_phone']}}</i></span>
								</div>
							@endif
							@if($certificateItem['delivery_address'])
								<div>
									<i class="fas fa-truck"></i>&nbsp;
									<span><i>{{ $certificateItem['delivery_address'] }}</i></span>
								</div>
							@endif--}}
						</div>
					@endif
				</td>
			</tr>
		@endforeach
	@endif
	</tbody>
</table>
