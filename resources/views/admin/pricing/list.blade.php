@if(!$products->isEmpty())
	<tr>
		<td>
			<table class="table table-hover table-sm table-bordered table-striped">
				<thead>
				<tr>
					<th class="text-center align-middle">Product</th>
					<th class="text-center align-middle">Amount</th>
					<th class="text-center align-middle d-none d-sm-table-cell">Discount</th>
					<th class="text-center align-middle d-none d-md-table-cell">Is active</th>
					{{--<th class="text-center align-middle d-none d-xl-table-cell">Validity</th>--}}
					<th class="text-center">Action</th>
				</tr>
				</thead>
				<tbody>
				@foreach($products as $product)
					@php
						$certificatePeriod = '-';
						if (isset($citiesProductsData[$city->id][$product->id])
							&& isset($citiesProductsData[$city->id][$product->id]['data_json'])
						) {
							$data = is_array($citiesProductsData[$city->id][$product->id]['data_json']) ? $citiesProductsData[$city->id][$product->id]['data_json'] : json_decode($citiesProductsData[$city->id][$product->id]['data_json'], true);

							if (!in_array($product->productType->alias, [app('\App\Models\ProductType')::SERVICES_ALIAS]) && isset($data['certificate_period'])) {
								$certificatePeriod = $data['certificate_period'];
								$certificatePeriod = $certificatePeriod ? (($certificatePeriod > 6) ? ($certificatePeriod / 12) . ' year' : $certificatePeriod . ' months') : 'termless';
							}
						}
					@endphp
					<tr class="odd @if(!$product->is_active) unactive @endif">
						<td class="align-middle">
							{{ $product->name }}
						</td>
						<td class="text-right align-middle">
							{{ isset($citiesProductsData[$city->id][$product->id]) ? $citiesProductsData[$city->id][$product->id]['currency'] . number_format($citiesProductsData[$city->id][$product->id]['price'], 2, '.', ' ') : '' }}
						</td>
						<td class="text-right align-middle d-none d-sm-table-cell">
							@if(isset($citiesProductsData[$city->id][$product->id]) && isset($citiesProductsData[$city->id][$product->id]['discount']))
								{{ $citiesProductsData[$city->id][$product->id]['discount']['value'] }} {{ $citiesProductsData[$city->id][$product->id]['discount']['is_fixed'] ? '' : '%' }}
							@endif
						</td>
						<td class="text-center align-middle d-none d-md-table-cell">
							@if(isset($citiesProductsData[$city->id][$product->id]))
								{{ $citiesProductsData[$city->id][$product->id]['is_active'] ? 'Yes' : 'No' }}
							@endif
						</td>
						{{--<td class="text-center align-middle d-none d-xl-table-cell">
							{{ $certificatePeriod }}
						</td>--}}
						<td class="text-center align-middle">
							<a href="javascript:void(0)" data-toggle="modal" data-url="/pricing/{{ $city->id }}/{{ $product->id }}/edit" data-action="/pricing/{{ $city->id }}/{{ $product->id }}" data-method="PUT" data-title="Edit" title="Edit">
								<i class="fa fa-edit" aria-hidden="true"></i>
							</a>
							@if(isset($citiesProductsData[$city->id][$product->id]))
								<a href="javascript:void(0)" data-toggle="modal" data-url="/pricing/{{ $city->id }}/{{ $product->id }}/delete" data-action="/pricing/{{ $city->id }}/{{ $product->id }}" data-method="DELETE" data-title="Delete" title="Delete">
									<i class="fa fa-trash" aria-hidden="true"></i>
								</a>
							@endif
						</td>
					</tr>
				@endforeach
				</tbody>
			</table>
		</td>
	</tr>
@endif
