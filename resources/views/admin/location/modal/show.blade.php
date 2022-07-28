<table class="table table-hover table-sm table-bordered table-striped">
	<tbody>
		<tr class="odd">
			<td>ID</td>
			<td>{{ $location->id }}</td>
		</tr>
		<tr class="odd">
			<td>Name</td>
			<td>{{ $location->name }}</td>
		</tr>
		<tr class="odd">
			<td>Alias</td>
			<td>{{ $location->alias }}</td>
		</tr>
		{{--<tr class="odd">
			<td>Юридическое лицо</td>
			<td>{{ $location->legalEntity ? $location->legalEntity->name : '' }}</td>
		</tr>--}}
		{{--<tr class="odd">
			<td>City</td>
			<td>{{ $location->city ? $location->city->name : '' }}</td>
		</tr>--}}
		<tr class="odd">
			<td>Is active</td>
			<td>{{ $location->is_active ? 'Yes' : 'No' }}</td>
		</tr>
		<tr class="odd">
			<td>Create date</td>
			<td>{{ $location->created_at }}</td>
		</tr>
		<tr class="odd">
			<td>Last edit date</td>
			<td>{{ $location->updated_at }}</td>
		</tr>
	</tbody>
</table>
