@foreach ($contractors as $contractor)
<tr class="odd @if(!$contractor->is_active) unactive @endif" data-id="{{ $contractor->id }}">
	<td class="text-center">
		{{ $contractor->name }}
	</td>
	<td class="text-center">
		{{ $contractor->lastname }}
	</td>
	<td class="text-center">
		{{ $contractor->email }}
	</td>
	<td class="text-center">
		{{ $contractor->phone }}
	</td>
	<td class="text-center">
		{{ $contractor->birthdate ? $contractor->birthdate->format('Y-m-d') : '' }}
	</td>
	<td class="text-center">
		{{ $contractor->discount()->valueFormatted() }}
	</td>
	<td class="text-center">
		{{ $contractor->getFlightTime() }} min
	</td>
	<td class="text-center align-middle d-none d-lg-table-cell">
		{{ $contractor->is_active ? 'Yes' : 'No' }}
	</td>
	{{--<td class="text-center">
		{{ $contractor->last_auth_at }}
	</td>--}}
	<td class="text-center">
		{{ $contractor->created_at }}
	</td>
	<td class="text-center">
		{{ $contractor->updated_at }}
	</td>
	{{--<td class="text-center">
		{{ $contractor->user ? $contractor->user->fio() : '' }}
	</td>--}}
	<td class="text-center align-middle">
		<a href="javascript:void(0)" data-toggle="modal" data-url="/contractor/{{ $contractor->id }}/edit" data-action="/contractor/{{ $contractor->id }}" data-method="PUT" data-type="contractor" data-title="Edit" title="Edit"><i class="fa fa-edit" aria-hidden="true"></i></a>
	</td>
</tr>
@endforeach