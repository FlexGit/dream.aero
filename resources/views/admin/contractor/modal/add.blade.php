<div class="row">
	<div class="col">
		<div class="form-group">
			<label for="name">Name</label>
			<input type="text" class="form-control" id="name" name="name" placeholder="Name">
		</div>
	</div>
	<div class="col">
		<div class="form-group">
			<label for="lastname">Last name</label>
			<input type="text" class="form-control" id="lastname" name="lastname" placeholder="Last name">
		</div>
	</div>
</div>
<div class="row">
	<div class="col">
		<div class="form-group">
			<label for="email">E-mail</label>
			<input type="email" class="form-control" id="email" name="email" placeholder="E-mail">
		</div>
	</div>
	<div class="col">
		<div class="form-group">
			<label for="phone">Phone</label>
			<input type="tel" class="form-control" id="phone" name="phone" placeholder="+12345678901">
		</div>
	</div>
</div>
<div class="row">
	<div class="col">
		<label for="birthdate">Birthdate</label>
		<input type="date" class="form-control" id="birthdate" name="birthdate" placeholder="Birthdate">
	</div>
	<div class="col">
		<div class="form-group">
			<label for="is_active">Is active</label>
			<select class="form-control" id="is_active" name="is_active">
				<option value="1" selected>Yes</option>
				<option value="0">No</option>
			</select>
		</div>
	</div>
	{{--<div class="col">
		<div class="form-group">
			<label for="is_subscribed">Подписан на рассылку</label>
			<select class="form-control" id="is_subscribed" name="is_subscribed">
				<option value="1" selected>Да</option>
				<option value="0">Нет</option>
			</select>
		</div>
	</div>--}}
</div>
