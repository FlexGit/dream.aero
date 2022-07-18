<table class="table table-hover table-sm table-bordered table-striped">
	<tbody>
		<tr class="odd">
			<td>ID</td>
			<td>{{ $promocode->id }}</td>
		</tr>
		<tr class="odd">
			<td>Number</td>
			<td>{{ $promocode->number }}</td>
		</tr>
		{{--<tr class="odd">
			<td>Город</td>
			<td>
				@foreach($promocode->cities ?? [] as $city)
					<div>{{ $city->name }}</div>
				@endforeach
			</td>
		</tr>
		<tr class="odd">
			<td>Локация</td>
			<td>{{ $promocode->location ? $promocode->location->name : '-' }}</td>
		</tr>--}}
		{{--<tr class="odd">
			<td>Client</td>
			<td>{{ $promocode->contractor ? $promocode->contractor->fio() : '-' }}</td>
		</tr>--}}
		<tr class="odd">
			<td>Discount</td>
			<td>{{ $promocode->discount ? $promocode->discount->valueFormatted() : '-' }}</td>
		</tr>
		<tr class="odd">
			<td>Is active</td>
			<td>{{ $promocode->is_active ? 'Yes' : 'No' }}</td>
		</tr>
		<tr class="odd">
			<td>Activity start date</td>
			<td>{{ $promocode->active_from_at }}</td>
		</tr>
		<tr class="odd">
			<td>Activity end date</td>
			<td>{{ $promocode->active_to_at }}</td>
		</tr>
		<tr class="odd">
			<td>Create date</td>
			<td>{{ $promocode->created_at }}</td>
		</tr>
		<tr class="odd">
			<td>Last edit date</td>
			<td>{{ $promocode->updated_at }}</td>
		</tr>
	</tbody>
</table>
