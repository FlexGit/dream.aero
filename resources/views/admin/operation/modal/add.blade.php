<div class="row">
	<div class="col">
		<div class="form-group">
			<label for="amount">Amount</label>
			<input type="text" class="form-control" id="amount" name="amount" placeholder="Amount">
		</div>
	</div>
	<div class="col">
		<div class="form-group">
			<label for="received_at">Date</label>
			<input type="date" class="form-control" id="received_at" name="received_at">
		</div>
	</div>
</div>
<div class="row">
	<div class="col">
		<div class="form-group">
			<label for="admin_id">Admin</label>
			<select class="form-control" id="admin_id" name="admin_id">
				<option></option>
				@foreach($users as $user)
					@if($user->role != app('\App\Models\User')::ROLE_ADMIN)
						@continue
					@endif
					<option value="{{ $user->id }}">{{ $user->fio() }}</option>
				@endforeach
			</select>
		</div>
	</div>
	<div class="col">
		<div class="form-group">
			<label for="pilot_id">Pilot</label>
			<select class="form-control" id="pilot_id" name="pilot_id">
				<option></option>
				@foreach($users as $user)
					@if($user->role != app('\App\Models\User')::ROLE_PILOT)
						@continue
					@endif
					<option value="{{ $user->id }}">{{ $user->fio() }}</option>
				@endforeach
			</select>
		</div>
	</div>
</div><div class="row">
	<div class="col">
		<div class="form-group">
			<label for="source">Source</label>
			<select class="form-control" id="source" name="source">
				<option></option>
				@foreach($sources ?? [] as $sourceAlias => $sourceName)
					<option value="{{ $sourceAlias }}">{{ $sourceName }}</option>
				@endforeach
			</select>
		</div>
	</div>
	<div class="col">
		<div class="form-group">
			<label for="deal_number">Deal #</label>
			<input type="text" class="form-control" id="deal_number" name="deal_number" placeholder="Deal #">
		</div>
	</div>
</div>
