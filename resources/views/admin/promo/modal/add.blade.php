<div class="form-group">
	<label for="name">Наименование</label>
	<input type="text" class="form-control" id="name" name="name" placeholder="Наименование">
</div>
<div class="form-group">
	<label for="alias">Алиас</label>
	<input type="text" class="form-control" id="alias" name="alias" placeholder="Алиас">
</div>
<div class="row">
	<div class="col">
		<div class="form-group">
			<label for="discount_id">Скидка</label>
			<select class="form-control" id="discount_id" name="discount_id">
				<option></option>
				@foreach($discounts ?? [] as $discount)
					<option value="{{ $discount->id }}">{{ $discount->valueFormatted() }}</option>
				@endforeach
			</select>
		</div>
	</div>
	<div class="col">
		<div class="form-group">
			<label for="city_id">Город</label>
			<select class="form-control" id="city_id" name="city_id">
				<option value="0">Все</option>
				@foreach($cities ?? [] as $city)
					<option value="{{ $city->id }}">{{ $city->name }}</option>
				@endforeach
			</select>
		</div>
	</div>
</div>
<div class="row">
	<div class="col">
		<div class="form-group">
			<label for="is_discount_booking_allow">Скидка действует на бронирование</label>
			<select class="form-control" id="is_discount_booking_allow" name="is_discount_booking_allow">
				<option value="1">Да</option>
				<option value="0" selected>Нет</option>
			</select>
		</div>
	</div>
	<div class="col">
		<div class="form-group">
			<label for="is_discount_certificate_purchase_allow">Скидка действует на покупку сертификата</label>
			<select class="form-control" id="is_discount_certificate_purchase_allow" name="is_discount_certificate_purchase_allow">
				<option value="1">Да</option>
				<option value="0" selected>Нет</option>
			</select>
		</div>
	</div>
</div>
<div class="form-group">
	<label for="preview_text">Краткое описание</label>
	<textarea class="form-control" id="preview_text" name="preview_text" rows="3"></textarea>
</div>
<div class="form-group">
	<label for="detail_text">Подробное описание</label>
	<textarea class="form-control tinymce" id="detail_text" name="detail_text" rows="5"></textarea>
</div>
<div class="row">
	<div class="col">
		<div class="form-group">
			<label for="is_published">Для публикации</label>
			<select class="form-control" id="is_published" name="is_published">
				<option value="1" selected>Да</option>
				<option value="0">Нет</option>
			</select>
		</div>
	</div>
	<div class="col">
		<div class="form-group">
			<label for="is_active">Активность</label>
			<select class="form-control" id="is_active" name="is_active">
				<option value="1" selected>Да</option>
				<option value="0">Нет</option>
			</select>
		</div>
	</div>
</div>
<div class="row">
	<div class="col">
		<div class="form-group">
			<label for="active_from_at">Дата начала активности</label>
			<input type="date" class="form-control" id="active_from_at" name="active_from_at" placeholder="Дата начала активности">
		</div>
	</div>
	<div class="col">
		<div class="form-group">
			<label for="active_to_at">Дата окончания активности</label>
			<input type="date" class="form-control" id="active_to_at" name="active_to_at" placeholder="Дата окончания активности">
		</div>
	</div>
</div>
<div class="form-group">
	<label for="image_file">Изображение</label>
	<div class="custom-file">
		<input type="file" class="custom-file-input" id="image_file" name="image_file">
		<label class="custom-file-label" for="image_file">Выбрать файл</label>
	</div>
</div>
<div class="form-group">
	<label for="meta_title">Meta Title</label>
	<input type="text" class="form-control" id="meta_title" name="meta_title" placeholder="Meta Title">
</div>
<div class="form-group">
	<label for="meta_description">Meta Description</label>
	<textarea class="form-control" id="meta_description" name="meta_description"></textarea>
</div>
