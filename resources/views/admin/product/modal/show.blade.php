<table id="productTable" class="table table-hover table-sm table-bordered table-striped">
	<tbody>
		<tr class="odd">
			<td>ID</td>
			<td>{{ $product->id }}</td>
		</tr>
		<tr class="odd">
			<td>Name</td>
			<td>{{ $product->name }}</td>
		</tr>
		<tr class="odd">
			<td>Public name</td>
			<td>{{ $product->public_name }}</td>
		</tr>
		<tr class="odd">
			<td>Alias</td>
			<td>{{ $product->alias }}</td>
		</tr>
		<tr class="odd">
			<td>Product type</td>
			<td>{{ $product->productType->name }}</td>
		</tr>
		<tr class="odd">
			<td>Duration, min</td>
			<td>{{ $product->duration }}</td>
		</tr>
		@if($product->productType && $product->productType->alias == app('\App\Models\ProductType')::VIP_ALIAS)
			<tr class="odd">
				<td>Pilot</td>
				<td>{{ optional($product->user)->fio() ?? '' }}</td>
			</tr>
		@endif
		<tr class="odd">
			<td>Description</td>
			<td>{{ isset($product->data_json['description']) ? $product->data_json['description'] : '' }}</td>
		</tr>
		<tr class="odd">
			<td>Is active</td>
			<td>{{ $product->is_active ? 'Yes' : 'No' }}</td>
		</tr>
		<tr class="odd">
			<td>Create date</td>
			<td>{{ $product->created_at }}</td>
		</tr>
		<tr class="odd">
			<td>Last edit date</td>
			<td>{{ $product->updated_at }}</td>
		</tr>
	</tbody>
</table>
