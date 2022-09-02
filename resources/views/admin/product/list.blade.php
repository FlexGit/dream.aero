@foreach ($products as $product)
<tr class="odd @if(!$product->is_active) unactive @endif">
	<td class="align-middle"><a href="javascript:void(0)" data-toggle="modal" data-url="/product/{{ $product->id }}/show" data-title="Show" title="Show">{{ $product->name }}</a></td>
	<td class="align-middle">{{ $product->public_name }}</td>
	<td class="align-middle">{{ $product->alias }}</td>
	<td class="align-middle">{{ $product->productType->name }}</td>
	<td class="text-right align-middle">{{ $product->duration > 0 ? $product->duration : '' }}</td>
	<td class="align-middle text-center">{{ $product->is_active ? 'Yes' : 'No' }}</td>
	<td class="text-center align-middle">
		<a href="javascript:void(0)" data-toggle="modal" data-url="/product/{{ $product->id }}/edit" data-action="/product/{{ $product->id }}" data-id="{{ $product->id }}" data-method="PUT" data-title="Edit" title="Edit">
			<i class="fa fa-edit" aria-hidden="true"></i>
		</a>
		<a href="javascript:void(0)" data-toggle="modal" data-target="#modal" data-url="/product/{{ $product->id }}/delete" data-action="/product/{{ $product->id }}" data-method="DELETE" data-title="Delete">
			<i class="fa fa-trash" aria-hidden="true"></i>
		</a>
	</td>
</tr>
@endforeach