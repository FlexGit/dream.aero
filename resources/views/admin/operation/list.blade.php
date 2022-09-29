@foreach ($operations as $operation)
	<tr class="odd">
		<td class="text-center">{{ $operation->operated_at->format('m/d/Y') }}</td>
		<td class="text-center">{{ $operation->operationType ? $operation->operationType->name : '' }}</td>
		<td class="text-center">{{ $operation->paymentMethod ? $operation->paymentMethod->name : '' }}</td>
		<td class="text-right">{{ number_format($operation->amount, 2, '.', ' ') }}</td>
		<td class="text-center">{{ isset($operation->data_json['comment']) ? $operation->data_json['comment'] : '' }}</td>
		<td class="text-center">
			<a href="javascript:void(0)" data-toggle="modal" data-url="/operation/{{ $operation->id }}/edit" data-action="/operation/{{ $operation->id }}" data-method="PUT" data-title="Edit Operation">
				<i class="fa fa-edit" aria-hidden="true"></i>
			</a>
			<a href="javascript:void(0)" data-toggle="modal" data-target="#modal" data-url="/operation/{{ $operation->id }}/delete" data-action="/operation/{{ $operation->id }}" data-method="DELETE" data-title="Delete Operation">
				<i class="fa fa-trash" aria-hidden="true"></i>
			</a>
		</td>
	</tr>
@endforeach
