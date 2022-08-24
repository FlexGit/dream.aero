@foreach ($users as $user)
<tr class="odd @if(!$user->enable) unactive @endif">
	<td>
		<a href="javascript:void(0)" data-toggle="modal" data-url="/user/{{ $user->id }}/show" data-title="Show" title="Show">
			{{ $user->fio() }}
		</a>
	</td>
	<td class="text-center">{{ $user->email }}</td>
	<td class="text-center">{{ isset($roles[$user->role]) ? $roles[$user->role] : '' }}</td>
	<td class="text-center">{{ $user->location ? $user->location->name : '-' }}</td>
	<td class="text-center">{{ $user->enable ? 'Yes' : 'No' }}</td>
	<td class="text-center align-middle">
		<a href="javascript:void(0)" data-toggle="modal" data-url="/user/{{ $user->id }}/edit" data-action="/user/{{ $user->id }}" data-method="PUT" data-title="Edit">
			<i class="fa fa-edit" aria-hidden="true"></i>
		</a>&nbsp;&nbsp;&nbsp;
	</td>
</tr>
@endforeach