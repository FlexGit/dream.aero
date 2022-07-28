@foreach ($locations as $location)
<tr class="odd">
	<td>
		<a href="javascript:void(0)" data-toggle="modal" data-url="/location/{{ $location->id }}/show" data-title="Show" title="Show">{{ $location->name }}</a>
	</td>
	<td class="text-center d-none d-sm-table-cell">{{ $location->alias }}</td>
	<td class="d-none d-md-table-cell">
		@foreach($location->simulators ?? [] as $simulator)
			<div>{{ $simulator->name }}</div>
		@endforeach
	</td>
	{{--<td class="d-none d-lg-table-cell">{{ $location->city ? $location->city->name : '' }}</td>--}}
	{{--<td class="d-none d-xl-table-cell">{{ $location->legalEntity ? $location->legalEntity->name : '' }}</td>--}}
	<td class="text-center d-none d-xl-table-cell">{{ $location->is_active ? 'Yes' : 'No' }}</td>
	<td class="text-center">
		<a href="javascript:void(0)" data-toggle="modal" data-url="/location/{{ $location->id }}/edit" data-action="/location/{{ $location->id }}" data-method="PUT" data-title="Edit">
			<i class="fa fa-edit" aria-hidden="true"></i>
		</a>&nbsp;&nbsp;&nbsp;
		{{--<a href="javascript:void(0)" data-toggle="modal" data-target="#modal" data-url="/location/{{ $location->id }}/delete" data-action="/location/{{ $location->id }}" data-method="DELETE" data-title="Delete">
			<i class="fa fa-trash" aria-hidden="true"></i>
		</a>--}}
	</td>
</tr>
@endforeach