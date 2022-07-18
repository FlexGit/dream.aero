<table class="table table-hover table-sm table-bordered table-striped">
	<tbody>
		<tr class="odd">
			<td>ID</td>
			<td>{{ $productType->id }}</td>
		</tr>
		<tr class="odd">
			<td>Name</td>
			<td>{{ $productType->name }}</td>
		</tr>
		<tr class="odd">
			<td>Alias</td>
			<td>{{ $productType->alias }}</td>
		</tr>
		<tr class="odd">
			<td>Is tariff</td>
			<td>{{ $productType->is_tariff ? 'Да' : 'Нет' }}</td>
		</tr>
		<tr class="odd">
			<td>Duration</td>
			<td>{{ array_key_exists('duration', $productType->data_json) ? implode(',', $productType->data_json['duration']) : '' }}</td>
		</tr>
		<tr class="odd">
			<td>Tax</td>
			<td>{{ $productType->tax }}%</td>
		</tr>
		<tr class="odd">
			<td>Is active</td>
			<td>{{ $productType->enable ? 'Да' : 'Нет' }}</td>
		</tr>
		<tr class="odd">
			<td>Create date</td>
			<td>{{ $productType->created_at }}</td>
		</tr>
		<tr class="odd">
			<td>Last edit date</td>
			<td>{{ $productType->updated_at }}</td>
		</tr>
	</tbody>
</table>
