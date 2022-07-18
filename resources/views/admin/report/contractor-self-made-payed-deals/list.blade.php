<table class="table table-sm table-bordered table-striped table-hover table-data">
	<thead>
		<tr>
			<th class="align-middle" style="text-align: center;vertical-align: middle;">City</th>
			<th class="align-middle" style="text-align: center;vertical-align: middle;">Location</th>
			<th class="align-middle" style="text-align: center;vertical-align: middle;">Count</th>
			<th class="align-middle" style="text-align: center;vertical-align: middle;">Amount</th>
		</tr>
	</thead>
	<tbody>
	@foreach($cities as $city)
		@foreach($city->locations as $location)
			<tr>
				<td class="align-middle text-center" style="text-align: center;vertical-align: middle;">
					{{ $city->name }}
				</td>
				<td class="align-middle text-center" style="text-align: center;vertical-align: middle;">
					{{ $location->name }}
				</td>
				<td class="align-middle text-right" style="text-align: right;vertical-align: middle;">
					{{ isset($items[$location->id]['bill_count']) ? number_format($items[$location->id]['bill_count'], 0, '.', ' ') : 0 }}
				</td>
				<td class="align-middle text-right" style="text-align: right;vertical-align: middle;">
					{{ isset($items[$location->id]['bill_amount_sum']) ? number_format($items[$location->id]['bill_amount_sum'], 0, '.', ' ') : 0 }}
				</td>
			</tr>
		@endforeach
	@endforeach
	</tbody>
</table>
