<table class="table table-hover table-sm table-bordered table-striped">
	<tbody>
		<tr class="odd">
			<td>ID</td>
			<td>{{ $operationType->id }}</td>
		</tr>
		<tr class="odd">
			<td>Name</td>
			<td>{{ $operationType->name }}</td>
		</tr>
		<tr class="odd">
			<td>Alias</td>
			<td>{{ $operationType->alias }}</td>
		</tr>
		<tr class="odd">
			<td>Is active</td>
			<td>{{ $operationType->is_active ? 'Yes' : 'No' }}</td>
		</tr>
		<tr class="odd">
			<td>Create date</td>
			<td>{{ $operationType->created_at }}</td>
		</tr>
		<tr class="odd">
			<td>Last edit date</td>
			<td>{{ $operationType->updated_at }}</td>
		</tr>
	</tbody>
</table>
