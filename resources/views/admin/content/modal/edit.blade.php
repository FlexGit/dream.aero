<input type="hidden" id="id" name="id" value="{{ $content->id }}">

<div class="form-group">
	<label for="title">@if($type == app('\App\Models\Content')::REVIEWS_TYPE) Name @else Title @endif</label>
	<input type="text" class="form-control" id="title" name="title" value="{{ $content->title }}" placeholder="@if($type == app('\App\Models\Content')::REVIEWS_TYPE) Name @else Title @endif">
</div>
<div class="row">
	@if(in_array($type, [app('\App\Models\Content')::REVIEWS_TYPE, app('\App\Models\Content')::PAGES_TYPE, app('\App\Models\Content')::PROMOBOX_TYPE, app('\App\Models\Content')::GALLERY_TYPE]))
		<input type="hidden" id="alias" name="alias" value="{{ $content->alias }}">
	@else
		<div class="col-4">
			<div class="form-group">
				<label for="alias">Alias</label>
				<input type="text" class="form-control" id="alias" name="alias" value="{{ $content->alias }}" placeholder="Alias">
			</div>
		</div>
	@endif
	@if($type != app('\App\Models\Content')::PAGES_TYPE)
		<div class="col-2">
			<div class="form-group">
				<label for="is_active">Is active</label>
				<select class="form-control" id="is_active" name="is_active">
					<option value="1" @if($content->is_active) selected @endif>Yes</option>
					<option value="0" @if(!$content->is_active) selected @endif>No</option>
				</select>
			</div>
		</div>
		<div class="col-3">
			<div class="form-group">
				<label for="published_at">@if($type == app('\App\Models\Content')::PROMOBOX_TYPE) Active start date @else Publication date @endif</label>
				<input type="date" class="form-control" id="published_at" name="published_at" value="{{ $content->published_at ? $content->published_at->format('Y-m-d') : '	' }}" placeholder="@if($type == app('\App\Models\Content')::PROMOBOX_TYPE) Active start date @else Publication date @endif">
			</div>
		</div>
		@if($type == app('\App\Models\Content')::PROMOBOX_TYPE)
			<div class="col-3">
				<div class="form-group">
					<label for="published_end_at">Active end date</label>
					<input type="date" class="form-control" id="published_end_at" name="published_end_at" value="{{ $content->published_end_at ? $content->published_end_at->format('Y-m-d') : '' }}" placeholder="Active end date">
				</div>
			</div>
		@endif
	@endif
</div>
@if($type != app('\App\Models\Content')::PAGES_TYPE)
	@if(!in_array($type, [app('\App\Models\Content')::GALLERY_TYPE]))
		<div class="form-group">
			<label for="preview_text">@if($type == app('\App\Models\Content')::REVIEWS_TYPE) Review @else Description @endif</label>
			<textarea class="form-control tinymce" id="preview_text" name="preview_text" @if($type == app('\App\Models\Content')::REVIEWS_TYPE) rows="5" @endif>{{ $content->preview_text }}</textarea>
		</div>
	@endif
	@if(!in_array($type, [app('\App\Models\Content')::PROMOBOX_TYPE, app('\App\Models\Content')::GALLERY_TYPE]))
		<div class="form-group">
			<label for="detail_text">@if($type == app('\App\Models\Content')::REVIEWS_TYPE) Response @else Detailed text @endif</label>
			<textarea class="form-control tinymce" id="detail_text" name="detail_text">{{ $content->detail_text }}</textarea>
		</div>
	@endif
	@if(!in_array($type, [app('\App\Models\Content')::REVIEWS_TYPE, app('\App\Models\Content')::PROMOBOX_TYPE]))
		<div class="form-group">
			<label for="photo_preview_file">Image</label>
			<div class="custom-file">
				<input type="file" class="custom-file-input" id="photo_preview_file" name="photo_preview_file">
				<label class="custom-file-label" for="photo_preview_file">Choose a file</label>
			</div>
			@if(isset($content->data_json['photo_preview_file_path']))
				<div>
					<img src="/upload/{{ $content->data_json['photo_preview_file_path'] }}" width="150" alt="">
					<br>
					<small>[<a href="javascript:void(0)" class="js-photo-preview-delete" data-id="{{ $content->id }}">delete</a>]</small>
				</div>
			@endif
		</div>
	@endif
	@if($type == app('\App\Models\Content')::GALLERY_TYPE)
		<div class="form-group">
			<label for="video_url">Video (Youtube link)</label>
			<input type="text" class="form-control" id="video_url" name="video_url" @if(isset($content->data_json['video_url'])) value="{{ $content->data_json['video_url'] }}" @endif placeholder="Video (Youtube link)">
		</div>
	@endif
@endif
@if(!in_array($type, [app('\App\Models\Content')::REVIEWS_TYPE, app('\App\Models\Content')::PROMOBOX_TYPE, app('\App\Models\Content')::GALLERY_TYPE]))
	<div class="form-group">
		<label for="meta_title">Meta Title</label>
		<input type="text" class="form-control" id="meta_title" name="meta_title" value="{{ $content->meta_title }}" placeholder="Meta Title">
	</div>
	<div class="form-group">
		<label for="meta_description">Meta Description</label>
		<textarea class="form-control" id="meta_description" name="meta_description">{{ $content->meta_description }}</textarea>
	</div>
@endif