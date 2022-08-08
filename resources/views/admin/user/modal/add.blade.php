<input type="hidden" id="id" name="id">

<div class="row">
	<div class="col">
		<div class="form-group">
			<label for="lastname">Lastname</label>
			<input type="text" class="form-control" id="lastname" name="lastname" placeholder="Lastname">
		</div>
		<div class="form-group">
			<label for="name">Name</label>
			<input type="text" class="form-control" id="name" name="name" placeholder="Name">
		</div>
		<div class="form-group">
			<label for="email">E-mail</label>
			<input type="email" class="form-control" id="email" name="email" placeholder="E-mail">
		</div>
	</div>
	<div class="col">
		<div class="form-group">
			<label for="role">Role</label>
			<select class="form-control" id="role" name="role">
				@foreach($roles ?? [] as $role => $roleName)
					<option value="{{ $role }}">{{ $roleName }}</option>
				@endforeach
			</select>
		</div>
		<div class="form-group">
			<label for="location_id">Location</label>
			<select class="form-control" id="location_id" name="location_id">
				<option></option>
				@foreach($locations ?? [] as $location)
					<option value="{{ $location->id }}" data-city_id="{{ $location->city_id }}">{{ $location->name }}</option>
				@endforeach
			</select>
		</div>
		<div class="form-group">
			<label for="enable">Is active</label>
			<select class="form-control" id="enable" name="enable">
				<option value="1" selected>Yes</option>
				<option value="0">No</option>
			</select>
		</div>
	</div>
</div>
