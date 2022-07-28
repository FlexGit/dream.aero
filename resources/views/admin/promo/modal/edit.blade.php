@php
	$isDiscountBookingAllow = $isDiscountCertificatePurchaseAllow = 0;
	$isDiscountBookingAllow = (is_array($promo->data_json) && array_key_exists('is_discount_booking_allow', $promo->data_json)) ? $promo->data_json['is_discount_booking_allow'] : 0;
	$isDiscountCertificatePurchaseAllow = (is_array($promo->data_json) && array_key_exists('is_discount_certificate_purchase_allow', $promo->data_json)) ? $promo->data_json['is_discount_certificate_purchase_allow'] : 0;
@endphp

<input type="hidden" id="id" name="id" value="{{ $promo->id }}">

<div class="form-group">
	<label for="name">Name</label>
	<input type="text" class="form-control" id="name" name="name" value="{{ $promo->name }}" placeholder="Name">
</div>
<div class="form-group">
	<label for="alias">Alias</label>
	<input type="text" class="form-control" id="alias" name="alias" value="{{ $promo->alias }}" placeholder="Alias">
</div>
<div class="row">
	<div class="col">
		<div class="form-group">
			<label for="discount_id">Discount</label>
			<select class="form-control" id="discount_id" name="discount_id">
				<option></option>
				@foreach($discounts ?? [] as $discount)
					<option value="{{ $discount->id }}" @if($discount->id == $promo->discount_id) selected @endif>{{ $discount->valueFormatted() }}</option>
				@endforeach
			</select>
		</div>
	</div>
	<div class="col">
		<div class="form-group">
			<label for="is_active">Is active</label>
			<select class="form-control" id="is_active" name="is_active">
				<option value="1" @if($promo->is_active) selected @endif>Yes</option>
				<option value="0" @if(!$promo->is_active) selected @endif>No</option>
			</select>
		</div>
	</div>
	{{--<div class="col">
		<div class="form-group">
			<label for="city_id">City</label>
			<select class="form-control" id="city_id" name="city_id">
				<option value="0">All</option>
				@foreach($cities ?? [] as $city)
					<option value="{{ $city->id }}" @if($city->id == $promo->city_id) selected @endif>{{ $city->name }}</option>
				@endforeach
			</select>
		</div>
	</div>--}}
</div>
{{--<div class="form-group">
	<label for="preview_text">Brief description</label>
	<textarea class="form-control" id="preview_text" name="preview_text" rows="3">{{ $promo->preview_text }}</textarea>
</div>
<div class="form-group">
	<label for="detail_text">Detailed description</label>
	<textarea class="form-control tinymce" id="detail_text" name="detail_text" rows="5">{{ $promo->detail_text }}</textarea>
</div>--}}
{{--<div class="row">--}}
	{{--<div class="col">
		<div class="form-group">
			<label for="is_published">For publication</label>
			<select class="form-control" id="is_published" name="is_published">
				<option value="1" @if($promo->is_published) selected @endif>Yes</option>
				<option value="0" @if(!$promo->is_published) selected @endif>No</option>
			</select>
		</div>
	</div>--}}
{{--</div>--}}
<div class="row">
	<div class="col">
		<div class="form-group">
			<label for="active_from_at">Activity start date</label>
			<input type="date" class="form-control" id="active_from_at" name="active_from_at" value="{{ $promo->active_from_at ? \Carbon\Carbon::parse($promo->active_from_at)->format('Y-m-d') : '' }}" placeholder="Activity start date">
		</div>
	</div>
	<div class="col">
		<div class="form-group">
			<label for="active_to_at">Activity end date</label>
			<input type="date" class="form-control" id="active_to_at" name="active_to_at" value="{{ $promo->active_to_at ? \Carbon\Carbon::parse($promo->active_to_at)->format('Y-m-d') : '' }}" placeholder="Activity end date">
		</div>
	</div>
</div>
{{--<div class="form-group">
	<label for="image_file">Image</label>
	<div class="custom-file">
		<input type="file" class="custom-file-input" id="image_file" name="image_file">
		<label class="custom-file-label" for="image_file">Choose a file</label>
	</div>
	@if(isset($promo->data_json['image_file_path']))
		<div>
			<img src="/upload/{{ $promo->data_json['image_file_path'] }}" width="150" alt="" style="border: 1px solid #ddd;margin-top: 10px;">
			<br>
			<small>[<a href="javascript:void(0)" class="js-image-delete" data-id="{{ $promo->id }}">delete</a>]</small>
		</div>
	@endif
</div>
<div class="form-group">
	<label for="meta_title">Meta Title</label>
	<input type="text" class="form-control" id="meta_title" name="meta_title" value="{{ $promo->meta_title }}" placeholder="Meta Title">
</div>
<div class="form-group">
	<label for="meta_description">Meta Description</label>
	<textarea class="form-control" id="meta_description" name="meta_description">{{ $promo->meta_description }}</textarea>
</div>--}}
