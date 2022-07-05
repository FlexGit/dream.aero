@foreach ($contractors as $contractor)
	@php
		$flightTime = $contractor->getFlightTime();
		/*$flightCnt = $contractor->getFlightCount();*/
		$status = $contractor->getStatus($statuses, $flightTime);
		$score = $contractor->getScore();
		/*$balance = $contractor->getBalance($statuses);*/
	@endphp
<tr class="odd" data-id="{{ $contractor->id }}">
	<td class="align-middle">
		<div class="col-12 text-nowrap">
			<div class="d-inline-block col-6 text-center align-top">
				<div>
					<a href="javascript:void(0)" data-toggle="modal" data-url="/contractor/{{ $contractor->id }}/edit" data-action="/contractor/{{ $contractor->id }}" data-method="PUT" data-type="contractor" data-title="Редактирование контрагента" title="Редактировать контрагента">{{ $contractor->name }} {{ $contractor->lastname ?? '' }}</a>
				</div>
				<div>
					{{ $contractor->city ? $contractor->city->name : '' }}
				</div>
				<div class="d-flex justify-content-between mt-2">
					<div>
						@if($contractor->is_subscribed)
							<small class="mr-1"><i class="fas fa-at" title="Подписан на рассылку" style="color: #ccc;"></i></small>
						@endif
						<small>{{ $contractor->source ? \App\Models\Contractor::SOURCES[$contractor->source] : '' }}</small>
					</div>
					@if($contractor->user)
						<small>{{ $contractor->user->name }}</small>
					@endif
				</div>
			</div>
			<div class="d-inline-block col-6 align-top ml-3">
				@if($contractor->phone)
					<div title="Телефон" class="text-wrap">
						<i class="fas fa-mobile-alt"></i> {{ implode(', ', explode(',', $contractor->phone)) }}
					</div>
				@endif
				<div title="E-mail">
					<i class="far fa-envelope"></i> {{ $contractor->email }}
				</div>
				@if($contractor->birthdate)
					<div title="Дата рождения">
						<i class="fas fa-birthday-cake"></i> {{ \Carbon\Carbon::parse($contractor->birthdate)->format('Y-m-d') }}
					</div>
				@endif
			</div>
		</div>
	</td>
	<td class="align-middle d-none d-lg-table-cell">
		<div class="col-12 text-nowrap">
			<div class="d-inline-block col-6 align-top">
				<div title="Время налета">
					<i class="fas fa-plane"></i> {{ $flightTime ? number_format($flightTime, 0, '.', ' ') : 0 }} мин
				</div>
				<div title="Количество баллов">
					<i class="far fa-star"></i> {{ $score ? number_format($score, 0, '.', ' ') : 0 }} баллов
				</div>
			</div>
			<div class="d-inline-block col-6 align-top">
				@if ($status)
					<div title="Статус">
						<i class="fas fa-medal" style="color: {{ array_key_exists('color', $status->data_json ?? []) ? $status->data_json['color'] : 'none' }};"></i> {{ $status->name }}
					</div>
					@if($status->discount)
						<div title="Скидка">
							<i class="fas fa-user-tag"></i> {{ $status->discount->valueFormatted() }}
						</div>
					@endif
				@endif
			</div>
			<div class="col-12 text-nowrap">
				<small>[<a href="javascript:void(0)" data-toggle="modal" data-url="/contractor/{{ $contractor->id }}/score" @if($user->isSuperAdmin()) data-action="/contractor/{{ $contractor->id }}/score" @endif data-method="POST" data-type="score" data-title="История начисления баллов и времени налета" title="История История начисления баллов и времени налета">История начисления</a>]</small>
			</div>
		</div>
	</td>
	{{--<td class="text-center align-middle d-none d-xl-table-cell">
		<div title="Баланс">
			<i class="fas fa-coins"></i>
			<span class="pl-2 pr-2" @if($balance < 0) style="background-color: #ffbdba;" @elseif($balance > 0) style="background-color: #e9ffc9;" @endif>
				{{ number_format($balance, 0, '.', ' ') }}
				</span>
		</div>
	</td>--}}
	<td class="text-center align-middle d-none d-lg-table-cell">
		{{ $contractor->is_active ? 'Да' : 'Нет' }}
	</td>
	{{--<td class="text-center align-middle">
		<a href="javascript:void(0)" data-toggle="modal" data-url="/contractor/{{ $contractor->id }}/edit" data-action="/contractor/{{ $contractor->id }}" data-id="{{ $contractor->id }}" data-method="PUT" data-title="Редактирование" title="Редактировать">
			<i class="fa fa-edit" aria-hidden="true"></i>
		</a>&nbsp;&nbsp;&nbsp;
		<a href="javascript:void(0)" data-toggle="modal" data-url="/contractor/{{ $contractor->id }}/delete" data-action="/contractor/{{ $contractor->id }}" data-id="2" data-method="DELETE" data-title="Удаление" title="Удалить">
			<i class="fa fa-trash" aria-hidden="true"></i>
		</a>
	</td>--}}
</tr>
@endforeach