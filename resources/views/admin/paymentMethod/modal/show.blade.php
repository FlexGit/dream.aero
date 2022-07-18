<table class="table table-hover table-sm table-bordered table-striped">
	<tbody>
		<tr class="odd">
			<td>ID</td>
			<td>{{ $paymentMethod->id }}</td>
		</tr>
		<tr class="odd">
			<td>Name</td>
			<td>{{ $paymentMethod->name }}</td>
		</tr>
		<tr class="odd">
			<td>Alias</td>
			<td>{{ $paymentMethod->alias }}</td>
		</tr>
		<tr class="odd">
			<td>Is active</td>
			<td>{{ $paymentMethod->is_active ? 'Yes' : 'No' }}</td>
		</tr>
		<tr class="odd">
			<td>Create date</td>
			<td>{{ $paymentMethod->created_at }}</td>
		</tr>
		<tr class="odd">
			<td>Last edit date</td>
			<td>{{ $paymentMethod->updated_at }}</td>
		</tr>
	</tbody>
</table>
