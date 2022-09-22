@foreach ($tips as $tip)
	<tr class="odd">
		<td class="text-center">{{ $tip->received_at->format('m/d/Y') }}</td>
		<td class="text-center">{{ $tip->admin ? $tip->admin->fio() : '' }}</td>
		<td class="text-center">{{ $tip->pilot ? $tip->pilot->fio() : '' }}</td>
		<td class="text-center">{{ isset($sources[$tip->source]) ? $sources[$tip->source] : $tip->source }}</td>
		<td class="text-center">{{ $tip->deal ? $tip->deal->number : '' }}</td>
		<td class="text-right">{{ number_format($tip->amount, 2, '.', ' ') }}</td>
		<td class="text-center">
			<a href="javascript:void(0)" data-toggle="modal" data-url="/tip/{{ $tip->id }}/edit" data-action="/tip/{{ $tip->id }}" data-method="PUT" data-title="Edit Tips">
				<i class="fa fa-edit" aria-hidden="true"></i>
			</a>
			<a href="javascript:void(0)" data-toggle="modal" data-target="#modal" data-url="/tip/{{ $tip->id }}/delete" data-action="/tip/{{ $tip->id }}" data-method="DELETE" data-title="Delete Tips">
				<i class="fa fa-trash" aria-hidden="true"></i>
			</a>
		</td>
	</tr>
@endforeach
