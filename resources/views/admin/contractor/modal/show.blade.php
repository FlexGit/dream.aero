<table id="contractorTable" class="table table-hover table-sm table-bordered table-striped">
	<thead>
	<tr>
		<th>Attribute</th>
		<th>Value</th>
	</tr>
	</thead>
	<tbody>
		<tr class="odd">
			<td>ID</td>
			<td>{{ $contractor->id }}</td>
		</tr>
		<tr class="odd">
			<td>Name</td>
			<td>{{ $contractor->name }}</td>
		</tr>
		<tr class="odd">
			<td>E-mail</td>
			<td>{{ $contractor->email }}</td>
		</tr>
		<tr class="odd">
			<td>Phone</td>
			<td>{{ $contractor->phone }}</td>
		</tr>
		<tr class="odd">
			<td>Скидка</td>
			<td>{{ $contractor->discount()->valueFormatted() }}</td>
		</tr>
		<tr class="odd">
			<td>Is active</td>
			<td>{{ $contractor->is_active ? 'Yes' : 'No' }}</td>
		</tr>
		<tr class="odd">
			<td>Last auth date</td>
			<td>{{ $contractor->last_auth_at }}</td>
		</tr>
		<tr class="odd">
			<td>Create date</td>
			<td>{{ $contractor->created_at }}</td>
		</tr>
		<tr class="odd">
			<td>Last edit date</td>
			<td>{{ $contractor->updated_at }}</td>
		</tr>
	</tbody>
</table>
