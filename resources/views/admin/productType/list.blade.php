@foreach ($productTypes as $productType)
<tr class="odd">
	<td>
		<a href="javascript:void(0)" data-toggle="modal" data-url="/product_type/{{ $productType->id }}/show" data-title="Show" title="Show">{{ $productType->name }}</a>
	</td>
	<td class="text-center">{{ $productType->alias }}</td>
	<td class="text-center">{{ $productType->is_tariff ? 'Yes' : 'No' }}</td>
	{{--<td class="text-center">{{ is_array($productType->data_json['duration']) ? implode(', ', $productType->data_json['duration']) : $productType->data_json['duration'] }}</td>--}}
	<td class="text-center">{{ $productType->tax }}%</td>
	<td class="text-center">{{ $productType->is_active ? 'Yes' : 'No' }}</td>
	<td class="text-center">
		<a href="javascript:void(0)" data-toggle="modal" data-url="/product_type/{{ $productType->id }}/edit" data-action="/product_type/{{ $productType->id }}" data-method="PUT" data-title="Edit">
			<i class="fa fa-edit" aria-hidden="true"></i>
		</a>&nbsp;&nbsp;&nbsp;
		<a href="javascript:void(0)" data-toggle="modal" data-target="#modal" data-url="/product_type/{{ $productType->id }}/delete" data-action="/product_type/{{ $productType->id }}" data-method="DELETE" data-title="Delete">
			<i class="fa fa-trash" aria-hidden="true"></i>
		</a>
	</td>
</tr>
@endforeach