@foreach ($paymentMethods as $paymentMethod)
<tr class="odd">
	<td>
		<a href="javascript:void(0)" data-toggle="modal" data-url="/payment_method/{{ $paymentMethod->id }}/show" data-title="Show" title="Show">{{ $paymentMethod->name }}</a>
	</td>
	<td class="text-center">{{ $paymentMethod->alias }}</td>
	<td class="text-center">{{ $paymentMethod->is_active ? 'Yes' : 'No' }}</td>
	<td class="text-center">
		<a href="javascript:void(0)" data-toggle="modal" data-url="/payment_method/{{ $paymentMethod->id }}/edit" data-action="/payment_method/{{ $paymentMethod->id }}" data-method="PUT" data-title="Edit">
			<i class="fa fa-edit" aria-hidden="true"></i>
		</a>&nbsp;&nbsp;&nbsp;
		<a href="javascript:void(0)" data-toggle="modal" data-target="#modal" data-url="/payment_method/{{ $paymentMethod->id }}/delete" data-action="/payment_method/{{ $paymentMethod->id }}" data-method="DELETE" data-title="Delete">
			<i class="fa fa-trash" aria-hidden="true"></i>
		</a>
	</td>
</tr>
@endforeach