<table class="table table-hover table-sm table-bordered table-striped">
	<tbody>
		<tr class="odd">
			<td>ID</td>
			<td>{{ $city->id }}</td>
		</tr>
		<tr class="odd">
			<td>Name</td>
			<td>{{ $city->name }}</td>
		</tr>
		<tr class="odd">
			<td>Alias</td>
			<td>{{ $city->alias }}</td>
		</tr>
		<tr class="odd">
			<td>E-mail</td>
			<td>{{ $city->email }}</td>
		</tr>
		<tr class="odd">
			<td>Phone number</td>
			<td>{{ $city->phone }}</td>
		</tr>
		<tr class="odd">
			<td>WhatsApp</td>
			<td>{{ $city->whatsapp }}</td>
		</tr>
		<tr class="odd">
			<td>Is active</td>
			<td>{{ $city->is_active ? 'Yes' : 'No' }}</td>
		</tr>
		<tr class="odd">
			<td>Create date</td>
			<td>{{ $city->created_at }}</td>
		</tr>
		<tr class="odd">
			<td>Last edit date</td>
			<td>{{ $city->updated_at }}</td>
		</tr>
	</tbody>
</table>
