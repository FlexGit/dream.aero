<input type="hidden" id="id" name="id" value="{{ $contractor->id }}">

<div class="row">
	<div class="col">
		<div class="form-group">
			<label for="name">Name</label>
			<input type="text" class="form-control" id="name" name="name" value="{{ $contractor->name }}" placeholder="Name">
		</div>
	</div>
	<div class="col">
		<div class="form-group">
			<label for="name">Last name</label>
			<input type="text" class="form-control" id="lastname" name="lastname" value="{{ $contractor->lastname }}" placeholder="Last name">
		</div>
	</div>
</div>
<div class="row">
	<div class="col">
		<div class="form-group">
			<label for="email">E-mail</label>
			<input type="email" class="form-control" id="email" name="email" value="{{ $contractor->email }}" placeholder="E-mail">
		</div>
	</div>
	<div class="col">
		<div class="form-group">
			<label for="phone">Phone</label>
			<input type="tel" class="form-control" id="phone" name="phone" value="{{ $contractor->phone }}" placeholder="+12345678901">
		</div>
	</div>
</div>
<div class="row">
	<div class="col">
		<label for="birthdate">Birthdate</label>
		<input type="date" class="form-control" id="birthdate" name="birthdate" value="{{ $contractor->birthdate ? \Carbon\Carbon::parse($contractor->birthdate)->format('Y-m-d') : '' }}" placeholder="Birthdate">
	</div>
	<div class="col">
		<div class="form-group">
			<label for="is_active">Is active</label>
			<select class="form-control" id="is_active" name="is_active">
				<option value="1" @if($contractor->is_active) selected @endif>Yes</option>
				<option value="0" @if(!$contractor->is_active) selected @endif>No</option>
			</select>
		</div>
	</div>
	{{--<div class="col">
		<div class="form-group">
			<label for="is_subscribed">Подписан на рассылку</label>
			<select class="form-control" id="is_subscribed" name="is_subscribed">
				<option value="1" @if($contractor->is_subscribed) selected @endif>Да</option>
				<option value="0" @if(!$contractor->is_subscribed) selected @endif>Нет</option>
			</select>
		</div>
	</div>--}}
</div>