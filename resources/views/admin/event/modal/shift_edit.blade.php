<input type="hidden" id="id" name="id" value="{{ $event->id }}">

<div class="row">
	<div class="col-6">
		<div class="form-group">
			<label for="user_id">User</label>
			<select class="form-control js-shift-user" id="user_id" name="user_id">
				@foreach($users as $user)
					@if($user->role == app('\App\Models\User')::ROLE_SUPERADMIN)
						@continue
					@endif

					<option value="{{ $user->id }}" data-role="{{ $user->role }}" @if($user->id == $event->user_id) selected @endif
						@if($event->user && $event->user->role == app('\App\Models\User')::ROLE_ADMIN && $user->role != app('\App\Models\User')::ROLE_ADMIN)
							class="hidden"
						@endif
						@if($event->user && $event->user->role == app('\App\Models\User')::ROLE_PILOT && $user->role != app('\App\Models\User')::ROLE_PILOT)
							class="hidden"
						@endif
					>{{ $user->fio() }}</option>
				@endforeach
			</select>
		</div>
	</div>
	<div class="col-6">
		<div class="form-group">
			<label for="period">Period</label>
			<div class="d-flex">
				<input type="time" class="form-control" id="start_at_time" name="start_at_time" value="{{ $event->start_at->format('H:i') }}">
				<input type="time" class="form-control ml-2" id="stop_at_time" name="stop_at_time" value="{{ $event->stop_at->format('H:i') }}">
			</div>
		</div>
	</div>
</div>
