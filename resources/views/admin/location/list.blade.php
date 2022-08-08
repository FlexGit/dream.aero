@foreach ($locations as $location)
<tr class="odd @if(!$location->is_active) unactive @endif">
	<td>
		<a href="javascript:void(0)" data-toggle="modal" data-url="/location/{{ $location->id }}/show" data-title="Show" title="Show">{{ $location->name }}</a>
	</td>
	<td class="text-center d-none d-sm-table-cell">{{ $location->alias }}</td>
	<td class="d-none d-md-table-cell">
		@foreach($location->simulators ?? [] as $simulator)
			<div>{{ $simulator->name }}</div>
		@endforeach
	</td>
	<td class="text-center d-none d-xl-table-cell">{{ $location->is_active ? 'Yes' : 'No' }}</td>
	<td class="text-center">
		<a href="javascript:void(0)" data-toggle="modal" data-url="/location/{{ $location->id }}/edit" data-action="/location/{{ $location->id }}" data-method="PUT" data-title="Edit">
			<i class="fa fa-edit" aria-hidden="true"></i>
		</a>&nbsp;&nbsp;&nbsp;
	</td>
</tr>
@endforeach