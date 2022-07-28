@foreach ($promos as $promo)
<tr class="odd">
	<td>
		<a href="javascript:void(0)" data-toggle="modal" data-url="/promo/{{ $promo->id }}/show" data-title="Show" title="Show">{{ $promo->name }}</a>
	</td>
	<td class="text-center d-none d-sm-table-cell">{{ $promo->alias }}</td>
	<td class="text-center d-none d-sm-table-cell">{{ $promo->discount ? $promo->discount->valueFormatted() : '' }}</td>
	{{--<td class="text-center d-none d-xl-table-cell">{{ $promo->is_published ? 'Yes' : 'No' }}</td>--}}
	<td class="text-center d-none d-xl-table-cell">{{ $promo->is_active ? 'Yes' : 'No' }}</td>
	<td class="text-center d-none d-xl-table-cell">{{ $promo->active_from_at ? \Carbon\Carbon::parse($promo->active_from_at)->format('Y-m-d') : '' }}</td>
	<td class="text-center d-none d-xl-table-cell">{{ $promo->active_to_at ? \Carbon\Carbon::parse($promo->active_to_at)->format('Y-m-d') : '' }}</td>
	<td class="text-center align-middle">
		<a href="javascript:void(0)" data-toggle="modal" data-url="/promo/{{ $promo->id }}/edit" data-action="/promo/{{ $promo->id }}" data-method="PUT" data-title="Edit">
			<i class="fa fa-edit" aria-hidden="true"></i>
		</a>
		<a href="javascript:void(0)" data-toggle="modal" data-target="#modal" data-url="/promo/{{ $promo->id }}/delete" data-action="/promo/{{ $promo->id }}" data-method="DELETE" data-title="Delete">
			<i class="fa fa-trash" aria-hidden="true"></i>
		</a>&nbsp;&nbsp;&nbsp;
	</td>
</tr>
@endforeach