<div class="form-group">
	<label for="title">@if($type == app('\App\Models\Content')::REVIEWS_TYPE) Name @else Title @endif</label>
	<input type="text" class="form-control" id="title" name="title" placeholder="@if($type == app('\App\Models\Content')::REVIEWS_TYPE) Name @else Title @endif">
</div>
<div class="row">
	@if(in_array($type, [app('\App\Models\Content')::REVIEWS_TYPE, app('\App\Models\Content')::PAGES_TYPE, app('\App\Models\Content')::PROMOBOX_TYPE, app('\App\Models\Content')::GALLERY_TYPE]))
		<input type="hidden" id="alias" name="alias" value="{{ (string)\Webpatser\Uuid\Uuid::generate() }}">
	@else
		<div class="col-4">
			<div class="form-group">
				<label for="alias">Alias</label>
				<input type="text" class="form-control" id="alias" name="alias" placeholder="Alias">
			</div>
		</div>
	@endif
	@if($type != app('\App\Models\Content')::PAGES_TYPE)
		<div class="col-2">
			<div class="form-group">
				<label for="is_active">Is active</label>
				<select class="form-control" id="is_active" name="is_active">
					<option value="1" selected>Yes</option>
					<option value="0">No</option>
				</select>
			</div>
		</div>
		<div class="col-3">
			<div class="form-group">
				<label for="published_at">@if($type == app('\App\Models\Content')::PROMOBOX_TYPE) Active start date @else Publication date @endif</label>
				<input type="date" class="form-control" id="published_at" name="published_at" placeholder="@if($type == app('\App\Models\Content')::PROMOBOX_TYPE) Active start date @else Publication date @endif">
			</div>
		</div>
		@if($type == app('\App\Models\Content')::PROMOBOX_TYPE)
			<div class="col-3">
				<div class="form-group">
					<label for="published_end_at">Active end date</label>
					<input type="date" class="form-control" id="published_end_at" name="published_end_at" placeholder="Active end date">
				</div>
			</div>
		@endif
	@endif
</div>
@if($type != app('\App\Models\Content')::PAGES_TYPE)
	@if(!in_array($type, [app('\App\Models\Content')::GALLERY_TYPE]))
		<div class="form-group">
			<label for="preview_text">@if($type == app('\App\Models\Content')::REVIEWS_TYPE) Review @else Description @endif</label>
			<textarea class="form-control tinymce" id="preview_text" name="preview_text" @if($type == app('\App\Models\Content')::REVIEWS_TYPE) rows="5" @endif></textarea>
		</div>
	@endif
	@if(!in_array($type, [app('\App\Models\Content')::PROMOBOX_TYPE, app('\App\Models\Content')::GALLERY_TYPE]))
		<div class="form-group">
			<label for="detail_text">@if($type == app('\App\Models\Content')::REVIEWS_TYPE) Response @else Detailed text @endif</label>
			<textarea class="form-control tinymce" id="detail_text" name="detail_text"></textarea>
		</div>
	@endif
	@if(!in_array($type, [app('\App\Models\Content')::REVIEWS_TYPE, app('\App\Models\Content')::PROMOBOX_TYPE]))
		<div class="form-group">
			<label for="photo_preview_file">Image</label>
			<div class="custom-file">
				<input type="file" class="custom-file-input" id="photo_preview_file" name="photo_preview_file">
				<label class="custom-file-label" for="photo_preview_file">Choose a file</label>
			</div>
		</div>
	@endif
	@if($type == app('\App\Models\Content')::GALLERY_TYPE)
		<div class="form-group">
			<label for="video_url">Video (Youtube link)</label>
			<input type="text" class="form-control" id="video_url" name="video_url" placeholder="Video (Youtube link)">
		</div>
	@endif
@endif
@if(!in_array($type, [app('\App\Models\Content')::REVIEWS_TYPE, app('\App\Models\Content')::PROMOBOX_TYPE, app('\App\Models\Content')::GALLERY_TYPE]))
	<div class="form-group">
		<label for="meta_title">Meta Title</label>
		<input type="text" class="form-control" id="meta_title" name="meta_title" placeholder="Meta Title">
	</div>
	<div class="form-group">
		<label for="meta_description">Meta Description</label>
		<textarea class="form-control" id="meta_description" name="meta_description"></textarea>
	</div>
@endif