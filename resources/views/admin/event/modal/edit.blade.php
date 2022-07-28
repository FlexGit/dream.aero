<input type="hidden" id="id" name="id" value="{{ $event->id }}">
<input type="hidden" id="comment_id" name="comment_id">
{{--<input type="hidden" id="position_id" name="position_id" value="{{ $event->deal_position_id }}">--}}
<input type="hidden" id="flight_simulator_id" name="flight_simulator_id" value="{{ $event->flight_simulator_id ?? 0 }}">
<input type="hidden" id="source" name="source" value="{{ app('\App\Models\Event')::EVENT_SOURCE_DEAL }}">

@switch($event->event_type)
	@case(app('\App\Models\Event')::EVENT_TYPE_DEAL)
		<ul class="nav nav-tabs">
			<li class="nav-item">
				<a class="nav-link active" data-toggle="tab" href="{{ asset('#flight') }}">Flight</a>
			</li>
			{{--<li class="nav-item">
				<a class="nav-link" data-toggle="tab" href="{{ asset('#deal-info') }}">Deal</a>
			</li>--}}
			{{--<li class="nav-item">
				<a class="nav-link" data-toggle="tab" href="{{ asset('#simulator') }}">Platform</a>
			</li>
			<li class="nav-item">
				<a class="nav-link" data-toggle="tab" href="{{ asset('#assessment') }}">Assessment</a>
			</li>--}}
			<li class="nav-item">
				<a class="nav-link" data-toggle="tab" href="{{ asset('#comments') }}">Comment</a>
			</li>
			<li class="nav-item">
				<a class="nav-link" data-toggle="tab" href="{{ asset('#notification') }}">Notification</a>
			</li>
			<li class="nav-item">
				<a class="nav-link" data-toggle="tab" href="{{ asset('#pilot') }}">Pilot</a>
			</li>
			<li class="nav-item">
				<a class="nav-link" data-toggle="tab" href="{{ asset('#doc') }}">Document</a>
			</li>
		</ul>

		<div class="tab-content">
			<div class="tab-pane container fade in show active" id="flight">
				@if($user->email == env('DEV_EMAIL'))
					<div class="row mt-3">
						<div class="col">
							<div class="form-group">
								<label>Uuid</label>
								<div class="d-flex">
									{{ $event->uuid }}
								</div>
							</div>
						</div>
					</div>
				@endif
				<div class="row mt-3">
					<div class="col">
						<div class="form-group">
							<label>Flight start</label>
							<div class="d-flex">
								<input type="date" class="form-control" name="start_at_date" value="{{ $event->start_at ? \Carbon\Carbon::parse($event->start_at)->format('Y-m-d') : '' }}" placeholder="">
								<input type="time" class="form-control ml-2" name="start_at_time" value="{{ $event->start_at ? \Carbon\Carbon::parse($event->start_at)->format('H:i') : '' }}" placeholder="">
							</div>
						</div>
					</div>
					<div class="col">
						<div class="form-group">
							<label>Flight end</label>
							<div class="d-flex">
								<input type="date" class="form-control" name="stop_at_date" value="{{ $event->stop_at ? \Carbon\Carbon::parse($event->stop_at)->format('Y-m-d') : '' }}" placeholder="">
								<input type="time" class="form-control ml-2" name="stop_at_time" value="{{ $event->stop_at ? \Carbon\Carbon::parse($event->stop_at)->format('H:i') : '' }}" placeholder="">
							</div>
						</div>
					</div>
					<div class="col">
						<div class="form-group">
							<label for="extra_time">Extra time</label>
							<select class="form-control" id="extra_time" name="extra_time">
								<option value="0" @if(!$event->extra_time) selected @endif>---</option>
								<option value="15" @if($event->extra_time == 15) selected @endif>15 min</option>
							</select>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-4">
						<div class="form-group">
							<label for="is_repeated_flight">Repeated flight</label>
							<select class="form-control" id="is_repeated_flight" name="is_repeated_flight">
								<option value="0" @if(!$event->is_repeated_flight) selected @endif>No</option>
								<option value="1" @if($event->is_repeated_flight) selected @endif>Yes</option>
							</select>
						</div>
					</div>
					<div class="col-4">
						<div class="form-group">
							<label for="is_unexpected_flight">Spontaneous flight</label>
							<select class="form-control" id="is_unexpected_flight" name="is_unexpected_flight">
								<option value="0" @if(!$event->is_unexpected_flight) selected @endif>No</option>
								<option value="1" @if($event->is_unexpected_flight) selected @endif>Yes</option>
							</select>
						</div>
					</div>
					@if($event->city && $event->city->locations->count() > 1)
						<div class="col-4">
							<div class="form-group">
								<label for="location_id">Location</label>
								<select class="form-control" id="location_id" name="location_id">
									@foreach($event->city->locations as $location)
										@foreach($location->simulators as $simulator)
											<option value="{{ $location->id }}" data-simulator_id="{{ $simulator->id }}" @if($event->location_id == $location->id && $event->flight_simulator_id == $simulator->id) selected @endif>{{ $location->name }} ({{ $simulator->name }})</option>
										@endforeach
									@endforeach
								</select>
							</div>
						</div>
					@endif
				</div>
				<div class="row">
					<div class="col-8">
						<div class="form-group">
							<label for="description">Description</label>
							<textarea class="form-control" id="description" name="description" rows="3" placeholder="">{{ $event->description ?? '' }}</textarea>
						</div>
					</div>
				</div>
			</div>
			{{--<div class="tab-pane fade" id="deal-info">
				<div class="row mt-3">
					<div class="col">
						<div class="text-center font-weight-bold">Client</div>
						@if($event->contractor)
							<div>{{ $event->contractor->fio() }}</div>
							<div><i class="fas fa-mobile-alt"></i> {{ $event->contractor->phoneFormatted() }}</div>
							<div><i class="far fa-envelope"></i> {{ $event->contractor->email }}</div>
						@endif
						<hr>
						<div class="text-center font-weight-bold">Contact person</div>
						@if($event->deal)
							<div>{{ $event->deal->name }}</div>
							<div><i class="fas fa-mobile-alt"></i> {{ $event->deal->phoneFormatted() }}</div>
							<div><i class="far fa-envelope"></i> {{ $event->deal->email }}</div>
						@endif
					</div>
					<div class="col">
						<div class="text-center font-weight-bold">Deal</div>
						@if($event->deal)
							<div>
								<a href="/deal/{{ $event->deal->id }}">{{ $event->deal->number ?? '' }}</a> от {{ $event->deal->created_at ? $event->deal->created_at->format('Y-m-d H:i') : '' }}
							</div>
							<div class="d-inline-block">
								@if($event->city)
									<i class="fas fa-dollar-sign"></i>
								@endif
								{{ number_format($event->deal->amount(), 0, '.', ' ') }}
							</div>
							--}}{{--@if($event->deal->scores)
								@php($scoreAmount = 0)
								@foreach($event->deal->scores ?? [] as $score)
									@if($score->type != app('\App\Models\Score')::USED_TYPE)
										@continue
									@endif
									@php($scoreAmount += abs($score->score))
								@endforeach
								@if($scoreAmount)
									<div class="d-inline-block" title="Оплачено баллами">
										<i class="far fa-star"></i> {{ number_format($scoreAmount, 0, '.', ' ') }}
									</div>
								@endif
							@endif--}}{{--
							<div class="d-inline-block" title="Итого к оплате">
								@php($balance = $event->deal->balance())
								@if($balance < 0)
									<span class="pl-2 pr-2" style="background-color: #ffbdba;">{{ number_format($balance, 0, '.', ' ') }}</span>
								@elseif($balance > 0)
									<span class="pl-2 pr-2" style="background-color: #e9ffc9;">+{{ number_format($balance, 0, '.', ' ') }}</span>
								@else
									<span class="pl-2 pr-2" style="background-color: #e9ffc9;">paid</span>
								@endif
							</div>
							@if($event->deal->status)
								<div class="text-center">
									<div class="p-0 pl-2 pr-2" style="background-color: {{ array_key_exists('color', $event->deal->status->data_json ?? []) ? $event->deal->status->data_json['color'] : 'none' }};">
										{{ $event->deal->status->name }}
									</div>
								</div>
							@endif
							@if(is_array($event->deal->data_json) && array_key_exists('comment', $event->deal->data_json) && $event->deal->data_json['comment'])
								<div class="text-left mt-2">
									<div style="line-height: 0.8em;border: 1px solid;border-radius: 10px;padding: 4px 8px;background-color: #fff;">
										<i class="far fa-comment-dots"></i> <i>{{ $event->deal->data_json['comment'] }}</i>
									</div>
								</div>
							@endif
							<div class="d-flex justify-content-between mt-2">
								<div>
									{{ isset(\App\Models\Deal::SOURCES[$event->deal->source]) ? \App\Models\Deal::SOURCES[$event->deal->source] : '' }}
								</div>
								<div>
									@if($event->deal->user)
										{{ $event->deal->user->name }}
									@endif
								</div>
							</div>
							<hr>
							<div class="text-center font-weight-bold">Invoices</div>
							@foreach($event->deal->bills ?? [] as $bill)
								<div># {{ $bill->number ?? '' }}, {{ $bill->created_at ? $bill->created_at->format('Y-m-d H:i') : '' }}</div>
								<div>
									@if($bill->currency)
										@if($bill->currency->alias == app('\App\Models\Currency')::USD_ALIAS)
											<i class="fas fa-dollar-sign"></i>
										@endif
									@endif
									{{ number_format($bill->amount, 0, '.', ' ') }}
									@if($bill->paymentMethod)
										[{{ $bill->paymentMethod->name }}]
										@if ($bill->paymentMethod->alias == app('\App\Models\PaymentMethod')::ONLINE_ALIAS)
											@if ($bill->link_sent_at)
												<i class="far fa-envelope-open"></i>
											@else
												<i class="far fa-envelope"></i>
											@endif
										@endif
									@endif
								</div>
								@if ($bill->status)
									<div class="text-center p-0 pl-2 pr-2" style="background-color: {{ array_key_exists('color', $bill->status->data_json ?? []) ? $bill->status->data_json['color'] : 'none' }};">
										{{ $bill->status->name }}
									</div>
								@endif
							@endforeach
						@endif
					</div>
					<div class="col">
						@if($event->dealPosition)
							<div class="text-center font-weight-bold">
								@if($event->dealPosition->certificate)
									Booking by voucher
								@else
									Booking
								@endif
							</div>
							<div>{{ $event->dealPosition->number ?? '' }}, {{ $event->dealPosition->created_at ? $event->dealPosition->created_at->format('Y-m-d H:i') : '' }}</div>
							--}}{{--@if($event->city)
								<div>
									<i class="fas fa-map-marker-alt"></i>
									{{ $event->city->name }}
									@if($event->location)
										{{ $event->location->name }}
									@endif
									@if($event->simulator)
										{{ $event->simulator->name }}
									@endif
								</div>
							@endif--}}{{--
							<div class="d-inline-block">
								@if($event->city)
									<i class="fas fa-dollar-sign"></i>
								@endif
								{{ number_format($event->dealPosition->amount, 0, '.', ' ') }} [{{ $event->dealPosition->bill->number ?? '' }}]
							</div>
							<div>
								<i class="far fa-calendar-alt" title="Desired flight time"></i> {{ \Carbon\Carbon::parse($event->dealPosition->flight_at)->format('Y-m-d H:i') }}
							</div>
							@if($event->dealPosition->promo)
								<div>
									<i class="fas fa-percent" title="Promo"></i> {{ $event->dealPosition->promo->name }}
								</div>
							@endif
							@if($event->dealPosition->promocode)
								<div>
									<i class="fas fa-tag" title="Promocode"></i> {{ $event->dealPosition->promocode->number ?? '' }}
								</div>
							@endif
							@if($event->dealPosition->product)
								<hr>
								<div class="text-center font-weight-bold">Product</div>
								<div>
									{{ $event->dealPosition->product->name }}
									[
									@if($event->dealPosition->currency)
										<i class="fas fa-dollar-sign"></i>
									@endif
									{{ $event->dealPosition->amount ? number_format($event->dealPosition->amount, 0, '.', ' ') : 'бесплатно' }}
									]
								</div>
							@endif
							@if($event->dealPosition->certificate)
								<hr>
								<div class="text-center font-weight-bold">Voucher</div>
								<a href="{{ route('getCertificate', ['uuid' => $event->dealPosition->certificate->uuid]) }}" target="_blank">
									<i class="far fa-file-alt" title="Voucher file"></i>
								</a>
								{{ $event->dealPosition->certificate->number ?: 'no number' }}
								@if ($event->dealPosition->certificate->sent_at)
									<i class="far fa-envelope-open" title="{{ $event->dealPosition->certificate->sent_at }}"></i>
								@else
									<i class="far fa-envelope" title="Voucher not sent yet"></i>
								@endif
								@if ($event->dealPosition->certificate->status)
									<div class="text-center p-0 pl-2 pr-2" style="background-color: {{ array_key_exists('color', $event->dealPosition->certificate->status->data_json ?? []) ? $event->dealPosition->certificate->status->data_json['color'] : 'none' }};">
										{{ $event->dealPosition->certificate->status->name }}
									</div>
								@endif
							@endif
							--}}{{--@if($event->deal->roistat)
								<hr>
								<div class="text-center font-weight-bold">Номер визита Roistat</div>
								<div>{{ $event->deal->roistat }}</div>
							@endif--}}{{--
						@endif
					</div>
				</div>
			</div>--}}
			{{--<div class="tab-pane fade" id="simulator">
				<div class="row mt-3">
					<div class="col-4">
						<div class="form-group">
							<label for="simulator_up_at">Platform lifting time</label>
							<input type="time" class="form-control" id="simulator_up_at" name="simulator_up_at" value="{{ $event->simulator_up_at ? $event->simulator_up_at->format('H:i') : '' }}">
						</div>
					</div>
					<div class="col-4">
						<div class="form-group">
							<label for="simulator_down_at">Platform lowering time</label>
							<input type="time" class="form-control" id="simulator_down_at" name="simulator_down_at" value="{{ $event->simulator_down_at ? $event->simulator_down_at->format('H:i') : '' }}">
						</div>
					</div>
				</div>
			</div>
			<div class="tab-pane fade" id="assessment">
				<div class="row mt-3">
					<div class="col-4">
						<div class="form-group">
							<label for="pilot_assessment">Оценка пилота</label>
							<select class="form-control" id="pilot_assessment" name="pilot_assessment">
								<option>---</option>
								@for($i=10;$i>0;$i--)
									<option value="{{ $i }}" @if($i == $event->pilot_assessment) selected @endif>{{ $i }}</option>
								@endfor
							</select>
						</div>
					</div>
					<div class="col-4">
						<div class="form-group">
							<label for="admin_assessment">Оценка админа</label>
							<select class="form-control" id="admin_assessment" name="admin_assessment">
								<option>---</option>
								@for($i=10;$i>0;$i--)
									<option value="{{ $i }}" @if($i == $event->admin_assessment) selected @endif>{{ $i }}</option>
								@endfor
							</select>
						</div>
					</div>
				</div>
			</div>--}}
			<div class="tab-pane fade" id="comments">
				<div class="pl-2 pr-2" style="line-height: 1.1em;">
					@foreach($comments ?? [] as $comment)
						<div class="d-flex justify-content-between mt-2 mb-2 pt-2 js-comment-container">
							<div style="width: 93%;">
								<div class="mb-0">
									<span class="comment-text" data-comment-id="{{ $comment['id'] }}">{{ $comment['name'] }}</span>
								</div>
								<div class="font-italic font-weight-normal mt-1 mb-0" style="line-height: 0.9em;border-top: 1px solid #bbb;">
									<small class="user-info" data-comment-id="{{ $comment['id'] }}">{{ $comment['wasUpdated'] }}: {{ $comment['user'] ?? '' }}, {{ $comment['date'] }}</small>
								</div>
							</div>
							<div class="d-flex">
								<div>
									<i class="far fa-edit js-comment-edit" data-comment-id="{{ $comment['id'] }}" title="Edit"></i>
								</div>
								<div class="ml-2">
									<i class="fas fa-trash-alt js-comment-remove" data-comment-id="{{ $comment['id'] }}" data-confirm-text="Are you sure?" title="Delete"></i>
								</div>
							</div>
						</div>
					@endforeach
				</div>
				<div class="form-group">
					<label for="comment"></label>
					<textarea class="form-control" id="comment" name="comment" rows="3" placeholder="Type the comment text"></textarea>
				</div>
			</div>
			<div class="tab-pane fade" id="notification">
				<div class="row pl-3 pr-3 mt-4">
					<div class="col-2">
						<div class="form-group">
							<div class="custom-control">
								<input type="radio" class="custom-control-input" id="notification_type_sms" name="notification_type" value="sms" @if($event->notification_type == app('\App\Models\Event')::NOTIFICATION_TYPE_SMS) checked @endif>
								<label class="custom-control-label" for="notification_type_sms">Sms</label>
							</div>
						</div>
					</div>
					<div class="col-2">
						<div class="form-group">
							<div class="custom-control">
								<input type="radio" class="custom-control-input" id="notification_type_call" name="notification_type" value="call" @if($event->notification_type == app('\App\Models\Event')::NOTIFICATION_TYPE_CALL) checked @endif>
								<label class="custom-control-label" for="notification_type_call">Call</label>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="tab-pane fade" id="pilot">
				<div class="row pl-3 pr-3 mt-4">
					<div class="col-4">
						<label>Shift pilots</label>
						<div>
							@foreach ($shifts as $shift)
								<div>
									{{ $shift->start_at->format('H:i') }} - {{ $shift->stop_at->format('H:i') }} - {{ $shift->user->fio() }}
								</div>
							@endforeach
						</div>
					</div>
					<div class="col-4">
						<div class="form-group">
							<label for="pilot_id">Actual pilot</label>
							<select class="form-control" id="pilot_id" name="pilot_id">
								<option value="0">---</option>
								@foreach($pilots as $pilot)
									<option value="{{ $pilot->id }}" @if($event->pilot_id == $pilot->id) selected @endif>{{ $pilot->fio() }}</option>
								@endforeach
							</select>
						</div>
					</div>
				</div>
			</div>
			<div class="tab-pane fade" id="doc">
				<div class="row pl-3 pr-3 mt-4">
					<div class="col-6">
						<div class="form-group">
							<label for="doc_file">Document photo</label>
							<div class="custom-file">
								<input type="file" class="custom-file-input" id="doc_file" name="doc_file">
								<label class="custom-file-label" for="doc_file">Choose a file</label>
								<div class="doc-file-container">
									@if(is_array($event->data_json) && array_key_exists('doc_file_path', $event->data_json) && $event->data_json['doc_file_path'])
										[ <a href="{{ url('/event/' . $event->uuid . '/doc/file') }}">download</a> ] [ <a href="javascript:void(0)" class="js-delete-doc-file" data-confirm-text="Are you sure you want to delete the file?">delete</a> ]
									@endif
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	@break
	@case(app('\App\Models\Event')::EVENT_TYPE_TEST_FLIGHT)
	<ul class="nav nav-tabs">
		<li class="nav-item">
			<a class="nav-link active" data-toggle="tab" href="{{ asset('#flight') }}">Flight</a>
		</li>
		<li class="nav-item">
			<a class="nav-link" data-toggle="tab" href="{{ asset('#simulator') }}">Platform</a>
		</li>
	</ul>

	<div class="tab-content">
		<div class="tab-pane container fade in show active" id="flight">
			@if($user->email == env('DEV_EMAIL'))
				<div class="row mt-3">
					<div class="col">
						<div class="form-group">
							<label>Uuid</label>
							<div class="d-flex">
								{{ $event->uuid }}
							</div>
						</div>
					</div>
				</div>
			@endif
			<div class="row mt-3">
				<div class="col">
					<div class="form-group">
						<label>Flight start</label>
						<div class="d-flex">
							<input type="date" class="form-control" name="start_at_date" value="{{ $event->start_at ? \Carbon\Carbon::parse($event->start_at)->format('Y-m-d') : '' }}" placeholder="">
							<input type="time" class="form-control ml-2" name="start_at_time" value="{{ $event->start_at ? \Carbon\Carbon::parse($event->start_at)->format('H:i') : '' }}" placeholder="">
						</div>
					</div>
				</div>
				<div class="col">
					<div class="form-group">
						<label>Flight end</label>
						<div class="d-flex">
							<input type="date" class="form-control" name="stop_at_date" value="{{ $event->stop_at ? \Carbon\Carbon::parse($event->stop_at)->format('Y-m-d') : '' }}" placeholder="">
							<input type="time" class="form-control ml-2" name="stop_at_time" value="{{ $event->stop_at ? \Carbon\Carbon::parse($event->stop_at)->format('H:i') : '' }}" placeholder="">
						</div>
					</div>
				</div>
				<div class="col">
					<div class="form-group">
						<label for="pilot_id">Pilot</label>
						<select class="form-control" id="pilot_id" name="pilot_id">
							<option value="0">---</option>
							@foreach($pilots as $pilot)
								<option value="{{ $pilot->id }}" @if($event->test_pilot_id == $pilot->id) selected @endif>{{ $pilot->fio() }}</option>
							@endforeach
						</select>
					</div>
				</div>
			</div>
		</div>
		{{--<div class="tab-pane fade" id="simulator">
			<div class="row mt-3">
				<div class="col-4">
					<div class="form-group">
						<label for="simulator_up_at">Время поднятия платформы</label>
						<input type="time" class="form-control" id="simulator_up_at" name="simulator_up_at" value="{{ $event->simulator_up_at ? $event->simulator_up_at->format('H:i') : '' }}">
					</div>
				</div>
				<div class="col-4">
					<div class="form-group">
						<label for="simulator_down_at">Время опускания платформы</label>
						<input type="time" class="form-control" id="simulator_down_at" name="simulator_down_at" value="{{ $event->simulator_down_at ? $event->simulator_down_at->format('H:i') : '' }}">
					</div>
				</div>
			</div>
		</div>--}}
	</div>
	@break
	@case(app('\App\Models\Event')::EVENT_TYPE_BREAK)
	@case(app('\App\Models\Event')::EVENT_TYPE_CLEANING)
	@case(app('\App\Models\Event')::EVENT_TYPE_USER_FLIGHT)
		<ul class="nav nav-tabs">
			<li class="nav-item">
				<a class="nav-link active" data-toggle="tab" href="{{ asset('#flight') }}">Flight</a>
			</li>
		</ul>

		<div class="tab-content">
			<div class="tab-pane container fade in show active" id="flight">
				@if($user->email == env('DEV_EMAIL'))
					<div class="row mt-3">
						<div class="col">
							<div class="form-group">
								<label>Uuid</label>
								<div class="d-flex">
									{{ $event->uuid }}
								</div>
							</div>
						</div>
					</div>
				@endif
				<div class="row mt-3">
					<div class="col">
						<div class="form-group">
							<label>Flight start</label>
							<div class="d-flex">
								<input type="date" class="form-control" name="start_at_date" value="{{ $event->start_at ? \Carbon\Carbon::parse($event->start_at)->format('Y-m-d') : '' }}" placeholder="">
								<input type="time" class="form-control ml-2" name="start_at_time" value="{{ $event->start_at ? \Carbon\Carbon::parse($event->start_at)->format('H:i') : '' }}" placeholder="">
							</div>
						</div>
					</div>
					<div class="col">
						<div class="form-group">
							<label>Flight end</label>
							<div class="d-flex">
								<input type="date" class="form-control" name="stop_at_date" value="{{ $event->stop_at ? \Carbon\Carbon::parse($event->stop_at)->format('Y-m-d') : '' }}" placeholder="">
								<input type="time" class="form-control ml-2" name="stop_at_time" value="{{ $event->stop_at ? \Carbon\Carbon::parse($event->stop_at)->format('H:i') : '' }}" placeholder="">
							</div>
						</div>
					</div>
					@if($event->event_type == app('\App\Models\Event')::EVENT_TYPE_USER_FLIGHT)
						<div class="col">
							<div class="form-group">
								<label for="employee_id">Employee</label>
								<select class="form-control" id="employee_id" name="employee_id">
									<option value="0">---</option>
									@foreach($employees as $employee)
										<option value="{{ $employee->id }}" @if($event->employee_id == $employee->id) selected @endif>{{ $employee->fio() }}</option>
									@endforeach
								</select>
							</div>
						</div>
					@endif
				</div>
			</div>
		</div>
	@break
	@case(app('\App\Models\Event')::EVENT_TYPE_SHIFT_ADMIN)
	@case(app('\App\Models\Event')::EVENT_TYPE_SHIFT_PILOT)
	@break
@endswitch
