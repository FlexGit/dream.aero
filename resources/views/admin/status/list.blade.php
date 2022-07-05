@foreach ($statuses as $status)
<tr class="odd">
	<td class="align-middle">
		<a href="javascript:void(0)" data-toggle="modal" data-url="/status/{{ $status->id }}/show" data-title="Просмотр" title="Посмотреть">{{ $status->name }}</a>
	</td>
	<td class="align-middle">{{ array_key_exists($status->type, $statusTypes) ? $statusTypes[$status->type] : $status->type }}</td>
	<td class="text-center align-middle d-none d-sm-table-cell">{{ $status->alias }}</td>
	<td class="align-middle d-none d-md-table-cell">
	@if($status->type == 'contractor' && $status->flight_time)
		Время налета: {{ number_format($status->flight_time, 0, '.', ' ') }} мин.
	@endif
	@if($status->discount)
		Скидка: {{ $status->discount->valueFormatted() }}.
	@endif
	@if($status->data_json && array_key_exists('color', $status->data_json))
		Цвет: {{ $status->data_json['color'] }}.
	@endif
	</td>
	<td class="text-center text-nowrap align-middle">
		<a href="javascript:void(0)" data-toggle="modal" data-url="/status/{{ $status->id }}/edit" data-action="/status/{{ $status->id }}" data-id="{{ $status->id }}" data-method="PUT" data-title="Редактирование" title="Редактировать">
			<i class="fa fa-edit" aria-hidden="true"></i>
		</a>
	</td>
</tr>
@endforeach