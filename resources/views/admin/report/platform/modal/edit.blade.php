<input type="hidden" id="id" name="id" value="{{ $platformData ? $platformData->id : 0}}">

<div class="text-center font-weight-bold mb-3">
	{{ $location->name }} {{ $simulator->name }}
</div>
<table class="table table-sm table-bordered table-striped">
	<thead>
	<tr>
		<th nowrap>Час</th>
		<th nowrap>IANM</th>
		<th nowrap>X-Plane</th>
		<th nowrap>Сервер</th>
		<th nowrap>Админ</th>
		<th nowrap>Календарь</th>
	</tr>
	</thead>
	<tbody>
		@foreach($intervals as $interval)
			<tr>
				<td class="align-middle text-center">{{ $interval->format('H') }}</td>
				<td class="align-top text-center">
					@if(isset($items['ianm'][$interval->format('H')]))
						@foreach ($items['ianm'][$interval->format('H')] as $item)
							<div>{{ $item['start_at'] }} - {{ $item['stop_at'] }}</div>
						@endforeach
					@endif
				</td>
				<td class="align-top text-center">
					@if(isset($items['in_air'][$interval->format('H')]))
						@foreach ($items['in_air'][$interval->format('H')] as $item)
							<div>{{ $item['start_at'] }} - {{ $item['stop_at'] }}</div>
						@endforeach
					@endif
				</td>
				<td class="align-top text-center">
					@if(isset($items['in_up'][$interval->format('H')]))
						@foreach ($items['in_up'][$interval->format('H')] as $item)
							<div>{{ $item['start_at'] }} - {{ $item['stop_at'] }}</div>
						@endforeach
					@endif
				</td>
				<td class="align-top text-center">
					@if(isset($items['admin'][$interval->format('H')]))
						@foreach ($items['admin'][$interval->format('H')] as $item)
							<div>{{ $item['start_at'] }} - {{ $item['stop_at'] }}</div>
						@endforeach
					@endif
				</td>
				<td class="align-top text-center">
					@if(isset($items['calendar'][$interval->format('H')]))
						@foreach ($items['calendar'][$interval->format('H')] as $item)
							<div>{{ $item['start_at'] }} - {{ $item['stop_at'] }}</div>
						@endforeach
					@endif
				</td>
			</tr>
		@endforeach
	</tbody>
</table>
<div class="row">
	<div class="col">
		<label for="comment">Комментарий</label>
		<textarea class="form-control" id="comment" name="comment" rows="3">{{ $platformData ? $platformData->comment : '' }}</textarea>
	</div>
</div>
