@foreach ($contents as $content)
<tr class="odd" data-id="{{ $content->id }}">
	<td class="align-middle">
		{{ $content->title }}
	</td>
	@switch($type)
		@case(app('\App\Models\Content')::PROMOBOX_TYPE)
			<td class="text-center align-middle">
				{{ $content->is_active ? 'Yes' : 'no' }}
			</td>
			<td class="text-center align-middle">
				{{ $content->published_at ? $content->published_at->format('m/d/Y') : '' }}
			</td>
			<td class="text-center align-middle">
				{{ $content->published_end_at ? $content->published_end_at->format('m/d/Y') : '' }}
			</td>
		@break
		@case(app('\App\Models\Content')::PAGES_TYPE)
		@break
		@default
			<td class="text-center align-middle">
				{{ $content->published_at ? $content->published_at->format('m/d/Y') : '' }}
			</td>
			<td class="text-center align-middle">
				{{ $content->is_active ? 'Yes' : 'no' }}
			</td>
		@break
	@endswitch
	<td class="text-center align-middle">
		<a href="javascript:void(0)" data-toggle="modal" data-url="/site/{{ $type }}/{{ $content->id }}/edit" data-action="/site/{{ $type }}/{{ $content->id }}" data-method="PUT" data-type="content" data-title="Edit" title="Edit">
			<i class="fa fa-edit" aria-hidden="true"></i>
		</a>&nbsp;
		<a href="javascript:void(0)" data-toggle="modal" data-url="/site/{{ $type }}/{{ $content->id }}/delete" data-action="/site/{{ $type }}/{{ $content->id }}" data-method="DELETE" data-title="Delete" title="Delete">
			<i class="fa fa-trash" aria-hidden="true"></i>
		</a>
	</td>
</tr>
@endforeach