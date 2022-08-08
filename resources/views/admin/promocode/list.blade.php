@foreach($promocodes as $promocode)
<tr class="odd @if(!$promocode->is_active) unactive @endif">
	<td>
		<a href="javascript:void(0)" data-toggle="modal" data-url="/promocode/{{ $promocode->id }}/show" data-title="Show" title="Show">{{ $promocode->number }}</a>
	</td>
	<td class="text-center d-none d-md-table-cell">{{ $promocode->discount ? $promocode->discount->valueFormatted() : '-' }}</td>
	<td class="text-center d-none d-md-table-cell">{{ $promocode->is_active ? 'Yes' : 'No' }}</td>
	<td class="text-center d-none d-xl-table-cell">{{ $promocode->active_from_at }}</td>
	<td class="text-center d-none d-xl-table-cell">{{ $promocode->active_to_at }}</td>
	<td class="text-center">
		<a href="javascript:void(0)" data-toggle="modal" data-url="/promocode/{{ $promocode->id }}/edit" data-action="/promocode/{{ $promocode->id }}" data-method="PUT" data-title="Edit">
			<i class="fa fa-edit" aria-hidden="true"></i>
		</a>
		<a href="javascript:void(0)" data-toggle="modal" data-target="#modal" data-url="/promocode/{{ $promocode->id }}/delete" data-action="/promocode/{{ $promocode->id }}" data-method="DELETE" data-title="Delete">
			<i class="fa fa-trash" aria-hidden="true"></i>
		</a>
	</td>
</tr>
@endforeach