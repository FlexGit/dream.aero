<table class="table table-hover table-sm table-bordered table-striped">
	<tbody>
		<tr class="odd">
			<td>ID</td>
			<td>{{ $promo->id }}</td>
		</tr>
		<tr class="odd">
			<td>Name</td>
			<td>{{ $promo->name }}</td>
		</tr>
		<tr class="odd">
			<td>Alias</td>
			<td>{{ $promo->alias }}</td>
		</tr>
		<tr class="odd">
			<td>Discount</td>
			<td>{{ $promo->discount ? $promo->discount->valueFormatted() : '' }}</td>
		</tr>
		{{--<tr class="odd">
			<td>City</td>
			<td>{{ $promo->city ? $promo->city->name : '' }}</td>
		</tr>--}}
		{{--<tr class="odd">
			<td>Brief description</td>
			<td>{{ $promo->preview_text }}</td>
		</tr>
		<tr class="odd">
			<td>Detailed description</td>
			<td>{{ strip_tags($promo->detail_text) }}</td>
		</tr>--}}
		{{--<tr class="odd">
			<td>For publication</td>
			<td>{{ $promo->is_published ? 'Yes' : 'No' }}</td>
		</tr>--}}
		<tr class="odd">
			<td>Is active</td>
			<td>{{ $promo->is_active ? 'Yes' : 'No' }}</td>
		</tr>
		<tr class="odd">
			<td>Activity start date</td>
			<td>{{ $promo->active_from_at ? \Carbon\Carbon::parse($promo->active_from_at)->format('Y-m-d') : '' }}</td>
		</tr>
		<tr class="odd">
			<td>Activity end date</td>
			<td>{{ $promo->active_to_at ? \Carbon\Carbon::parse($promo->active_to_at)->format('Y-m-d') : '' }}</td>
		</tr>
		{{--<tr class="odd">
			<td>Image</td>
			<td>
				@if(isset($promo->data_json['image_file_path']) && $promo->data_json['image_file_path'])
					<img src="/upload/{{ $promo->data_json['image_file_path'] }}" width="150" alt="">
				@endif
			</td>
		</tr>--}}
		<tr class="odd">
			<td>Create date</td>
			<td>{{ $promo->created_at }}</td>
		</tr>
		<tr class="odd">
			<td>Last edit date</td>
			<td>{{ $promo->updated_at }}</td>
		</tr>
	</tbody>
</table>
