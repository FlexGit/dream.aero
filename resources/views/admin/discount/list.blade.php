@foreach ($discounts as $discount)
<tr class="odd @if(!$discount->is_active) unactive @endif">
	<td class="text-center">{{ $discount->valueFormatted() }}</td>
	<td class="text-center">{{ $discount->is_fixed ? 'Yes' : 'No' }}</td>
	<td class="text-center">{{ $discount->is_active ? 'Yes' : 'No' }}</td>
	<td class="text-center">
		<a href="javascript:void(0)" data-toggle="modal" data-url="/discount/{{ $discount->id }}/edit" data-action="/discount/{{ $discount->id }}" data-method="PUT" data-title="Edit">
			<i class="fa fa-edit" aria-hidden="true"></i>
		</a>
		<a href="javascript:void(0)" data-toggle="modal" data-target="#modal" data-url="/discount/{{ $discount->id }}/delete" data-action="/discount/{{ $discount->id }}" data-method="DELETE" data-title="Delete">
			<i class="fa fa-trash" aria-hidden="true"></i>
		</a>
	</td>
</tr>
@endforeach