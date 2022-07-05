@foreach ($revisionData as $revision)
	@if ($revision['key'] == 'id')
		@continue
	@endif
	<tr class="odd" data-id="{{ $revision['id'] }}">
		<td class="align-middle">{{ $revision['revisionable_type'] }}</td>
		<td class="align-middle d-none d-sm-table-cell">
			{{ $revision['object'] }}
		</td>
		<td class="align-middle d-none d-md-table-cell">
			{!! $revision['linkedObject'] !!}
		</td>
		<td class="align-middle d-none d-md-table-cell">
			{{ \App\Services\HelpFunctions::getModelAttributeName($revision['entity'], $revision['key']) }}
		</td>
		<td class="d-none align-middle d-md-table-cell">
			{!! is_array(json_decode($revision['old_value'], true)) ? \App\Services\HelpFunctions::outputDiffTypeData($revision['entity'], json_decode($revision['old_value'], true), '') : $revision['old_value'] !!}
		</td>
		<td class="d-none align-middle d-md-table-cell">
			{!! is_array(json_decode($revision['new_value'], true)) ? \App\Services\HelpFunctions::outputDiffTypeData($revision['entity'], json_decode($revision['new_value'], true), '') : $revision['new_value'] !!}
		</td>
		<td class="text-center align-middle d-none d-md-table-cell">{{ $revision['user'] }}</td>
		<td class="text-center align-middle d-none d-xl-table-cell">{{ $revision['created_at'] }}</td>
	</tr>
@endforeach