@foreach ($contents as $content)
<tr class="odd" data-id="{{ $content->id }}">
	<td class="align-middle">
		{{--<a href="javascript:void(0)" data-toggle="modal" data-url="/content/{{ $content->id }}/edit" data-action="/content/{{ $content->id }}" data-method="PUT" data-type="content" data-title="Редактирование" title="Редактировать">--}}{{ $content->title }}{{--</a>--}}
	</td>
	<td class="text-center align-middle d-none d-lg-table-cell">
		{{ $content->city ? $content->city->name : 'Any' }}
	</td>
	@if($type != app('\App\Models\Content')::PAGES_TYPE) {
		<td class="text-center align-middle d-none d-xl-table-cell">
			{{ $content->published_at ? $content->published_at->format('Y-m-d') : '' }}
		</td>
		<td class="text-center align-middle d-none d-xl-table-cell">
			{{ $content->is_active ? 'Yes' : 'no' }}
		</td>
	@endif
	<td class="text-center align-middle">
		<a href="javascript:void(0)" data-toggle="modal" data-url="/site/{{ $type }}/{{ $content->id }}/edit" data-action="/site/{{ $type }}/{{ $content->id }}" data-method="PUT" data-type="content" data-title="Edit" title="Edit">
			<i class="fa fa-edit" aria-hidden="true"></i>
		</a>&nbsp;&nbsp;&nbsp;
		<a href="javascript:void(0)" data-toggle="modal" data-url="/site/{{ $type }}/{{ $content->id }}/delete" data-action="/site/{{ $type }}/{{ $content->id }}" data-method="DELETE" data-title="Delete" title="Delete">
			<i class="fa fa-trash" aria-hidden="true"></i>
		</a>
	</td>
</tr>
@endforeach