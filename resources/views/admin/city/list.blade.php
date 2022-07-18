@foreach ($cities as $city)
<tr class="odd">
	<td>
		<a href="javascript:void(0)" data-toggle="modal" data-url="/city/{{ $city->id }}/show" data-title="Просмотр" title="Посмотреть">{{ $city->name }}</a>
	</td>
	<td class="text-center d-none d-sm-table-cell">{{ $city->alias }}</td>
	<td class="text-center d-none d-md-table-cell">{{ $city->email }}</td>
	<td class="text-center d-none d-lg-table-cell">{{ $city->phone }}</td>
	<td class="text-center d-none d-xl-table-cell">{{ $city->is_active ? 'Да' : 'Нет' }}</td>
	<td class="text-center">
		<a href="javascript:void(0)" data-toggle="modal" data-url="/city/{{ $city->id }}/edit" data-action="/city/{{ $city->id }}" data-method="PUT" data-title="Редактирование">
			<i class="fa fa-edit" aria-hidden="true"></i>
		</a>&nbsp;&nbsp;&nbsp;
		<a href="javascript:void(0)" data-toggle="modal" data-target="#modal" data-url="/city/{{ $city->id }}/delete" data-action="/city/{{ $city->id }}" data-method="DELETE" data-title="Удаление">
			<i class="fa fa-trash" aria-hidden="true"></i>
		</a>
	</td>
</tr>
@endforeach