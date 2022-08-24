@foreach ($productTypes as $productType)
<tr class="odd">
	<td>
		<a href="javascript:void(0)" data-toggle="modal" data-url="/product_type/{{ $productType->id }}/show" data-title="Show" title="Show">{{ $productType->name }}</a>
	</td>
	<td class="text-center">{{ $productType->alias }}</td>
	<td class="text-center">{{ $productType->tax }}%</td>
	<td class="text-center">
		<a href="javascript:void(0)" data-toggle="modal" data-url="/product_type/{{ $productType->id }}/edit" data-action="/product_type/{{ $productType->id }}" data-method="PUT" data-title="Edit">
			<i class="fa fa-edit" aria-hidden="true"></i>
		</a>
	</td>
</tr>
@endforeach