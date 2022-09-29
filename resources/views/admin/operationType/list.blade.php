@foreach ($operationTypes as $operationType)
<tr class="odd @if(!$operationType->is_active) unactive @endif">
	<td>
		<a href="javascript:void(0)" data-toggle="modal" data-url="/operation_type/{{ $operationType->id }}/show" data-title="Show" title="Show">{{ $operationType->name }}</a>
	</td>
	<td class="text-center">{{ $operationType->alias }}</td>
	<td class="text-center">{{ $operationType->is_active ? 'Yes' : 'No' }}</td>
	<td class="text-center">
		<a href="javascript:void(0)" data-toggle="modal" data-url="/operation_type/{{ $operationType->id }}/edit" data-action="/operation_type/{{ $operationType->id }}" data-method="PUT" data-title="Edit">
			<i class="fa fa-edit" aria-hidden="true"></i>
		</a>
	</td>
</tr>
@endforeach