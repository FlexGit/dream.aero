@foreach ($cities as $city)
<tr class="odd @if(!$city->is_active) unactive @endif">
	<td>
		<a href="javascript:void(0)" data-toggle="modal" data-url="/city/{{ $city->id }}/show" data-title="Show" title="Show">{{ $city->name }}</a>
	</td>
	<td class="text-center">{{ $city->alias }}</td>
	<td class="text-center">{{ $city->email }}</td>
	<td class="text-center">{{ $city->phone }}</td>
	<td class="text-center">{{ $city->is_active ? 'Yes' : 'No' }}</td>
	<td class="text-center">
		<a href="javascript:void(0)" data-toggle="modal" data-url="/city/{{ $city->id }}/edit" data-action="/city/{{ $city->id }}" data-method="PUT" data-title="Edit">
			<i class="fa fa-edit" aria-hidden="true"></i>
		</a>
	</td>
</tr>
@endforeach