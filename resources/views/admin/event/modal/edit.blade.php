<input type="hidden" id="id" name="id" value="{{ $event->id }}">
<input type="hidden" id="comment_id" name="comment_id">
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
							<label>Flight stop</label>
							<div class="d-flex">
								<input type="date" class="form-control" name="stop_at_date" value="{{ $event->stop_at ? \Carbon\Carbon::parse($event->stop_at)->format('Y-m-d') : '' }}" placeholder="">
								<input type="time" class="form-control ml-2" name="stop_at_time" value="{{ $event->stop_at ? \Carbon\Carbon::parse($event->stop_at)->format('H:i') : '' }}" placeholder="">
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col">
						<div class="form-group">
							<label for="extra_time">Extra time</label>
							<select class="form-control" id="extra_time" name="extra_time">
								<option value="0" @if(!$event->extra_time) selected @endif>---</option>
								<option value="15" @if($event->extra_time == 15) selected @endif>15 min</option>
							</select>
						</div>
					</div>
					<div class="col">
						<div class="form-group">
							<label for="is_repeated_flight">Repeated flight</label>
							<select class="form-control" id="is_repeated_flight" name="is_repeated_flight">
								<option value="0" @if(!$event->is_repeated_flight) selected @endif>No</option>
								<option value="1" @if($event->is_repeated_flight) selected @endif>Yes</option>
							</select>
						</div>
					</div>
					<div class="col">
						<div class="form-group">
							<label for="is_unexpected_flight">Spontaneous flight</label>
							<select class="form-control" id="is_unexpected_flight" name="is_unexpected_flight">
								<option value="0" @if(!$event->is_unexpected_flight) selected @endif>No</option>
								<option value="1" @if($event->is_unexpected_flight) selected @endif>Yes</option>
							</select>
						</div>
					</div>
				</div>
			</div>
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
									{{ $shift->start_at->format('g:i A') }} - {{ $shift->stop_at->format('g:i A') }} - {{ $shift->user->fio() }}
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
					<div class="col">
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
		{{--<li class="nav-item">
			<a class="nav-link" data-toggle="tab" href="{{ asset('#simulator') }}">Platform</a>
		</li>--}}
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
						<label>Flight stop</label>
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
							<label>Flight stop</label>
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
