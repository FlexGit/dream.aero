<table class="table table-hover table-sm table-bordered table-striped">
	<tbody>
		<tr class="odd">
			<td>ID</td>
			<td>{{ $user->id }}</td>
		</tr>
		<tr class="odd">
			<td>Surname</td>
			<td>{{ $user->lastname }}</td>
		</tr>
		<tr class="odd">
			<td>Name</td>
			<td>{{ $user->name }}</td>
		</tr>
		{{--<tr class="odd">
			<td>Отчество</td>
			<td>{{ $user->middlename }}</td>
		</tr>
		<tr class="odd">
			<td>Дата рождения</td>
			<td>{{ \Carbon\Carbon::parse($user->birthdate)->format('d.m.Y') }}</td>
		</tr>--}}
		<tr class="odd">
			<td>E-mail</td>
			<td>{{ $user->email }}</td>
		</tr>
		{{--<tr class="odd">
			<td>Телефон</td>
			<td>{{ $user->phone }}</td>
		</tr>
		<tr class="odd">
			<td>Должность</td>
			<td>{{ $user->position }}</td>
		</tr>
		<tr class="odd">
			<td>Резервный сотрудник</td>
			<td>{{ $user->is_reserved ? 'Да' : 'Нет' }}</td>
		</tr>
		<tr class="odd">
			<td>Офиц. трудоустройство</td>
			<td>{{ $user->is_official ? 'Да' : 'Нет' }}</td>
		</tr>--}}
		<tr class="odd">
			<td>Role</td>
			<td>{{ isset($roles[$user->role]) ? $roles[$user->role] : '' }}</td>
		</tr>
		<tr class="odd">
			<td>City</td>
			<td>{{ $user->city ? $user->city->name : 'Any' }}</td>
		</tr>
		<tr class="odd">
			<td>Location</td>
			<td>{{ $user->location ? $user->location->name : 'Any' }}</td>
		</tr>
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
