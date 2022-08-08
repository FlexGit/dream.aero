<input type="hidden" id="id" name="id" value="{{ $user->id }}">

<div class="row">
	<div class="col">
		<div class="form-group">
			<label for="name">Lastname</label>
			<input type="text" class="form-control" id="lastname" name="lastname" value="{{ $user->lastname }}" placeholder="Lastname">
		</div>
		<div class="form-group">
			<label for="name">Name</label>
			<input type="text" class="form-control" id="name" name="name" value="{{ $user->name }}" placeholder="Name">
		</div>
		<div class="form-group">
			<label for="email">E-mail</label>
			<input type="email" class="form-control" id="email" name="email" value="{{ $user->email }}" placeholder="E-mail">
		</div>
	</div>
	<div class="col">
		<div class="form-group">
			<label for="role">Role</label>
			<select class="form-control" id="role" name="role">
				@foreach($roles ?? [] as $role => $roleName)
					<option value="{{ $role }}" @if($role == $user->role) selected @endif>{{ $roleName }}</option>
				@endforeach
			</select>
		</div>
		<div class="form-group">
			<label for="location_id">Location</label>
			<select class="form-control" id="location_id" name="location_id">
				<option></option>
				@foreach($locations ?? [] as $location)
					<option value="{{ $location->id }}" data-city_id="{{ $location->city_id }}" @if($location->id == $user->location_id) selected @endif>{{ $location->name }}</option>
				@endforeach
			</select>
		</div>
		<div class="form-group">
			<label for="enable">Is active</label>
			<select class="form-control" id="enable" name="enable">
				<option value="1" @if($user->enable) selected @endif>Yes</option>
				<option value="0" @if(!$user->enable) selected @endif>No</option>
			</select>
		</div>
	</div>
</div>
