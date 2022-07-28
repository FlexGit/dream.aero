<table class="table table-hover table-sm table-bordered table-striped">
	<tbody>
		<tr class="odd">
			<td>ID</td>
			<td>{{ $user->id }}</td>
		</tr>
		<tr class="odd">
			<td>Lastname</td>
			<td>{{ $user->lastname }}</td>
		</tr>
		<tr class="odd">
			<td>Name</td>
			<td>{{ $user->name }}</td>
		</tr>
		<tr class="odd">
			<td>E-mail</td>
			<td>{{ $user->email }}</td>
		</tr>
		<tr class="odd">
			<td>Role</td>
			<td>{{ isset($roles[$user->role]) ? $roles[$user->role] : '' }}</td>
		</tr>
		{{--<tr class="odd">
			<td>City</td>
			<td>{{ $user->city ? $user->city->name : 'Any' }}</td>
		</tr>
		<tr class="odd">
			<td>Location</td>
			<td>{{ $user->location ? $user->location->name : 'Any' }}</td>
		</tr>--}}
		<tr class="odd">
			<td>Is active</td>
			<td>{{ $user->enable ? 'Yes' : 'No' }}</td>
		</tr>
		<tr class="odd">
			<td>Create date</td>
			<td>{{ $user->created_at }}</td>
		</tr>
		<tr class="odd">
			<td>Last edit date</td>
			<td>{{ $user->updated_at }}</td>
		</tr>
	</tbody>
</table>
