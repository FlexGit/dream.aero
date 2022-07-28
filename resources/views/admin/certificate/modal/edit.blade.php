<input type="hidden" id="id" name="id" value="{{ $certificate->id }}">

<div class="row">
	<div class="col">
		<div class="form-group">
			<label for="number">Number</label>
			<input type="text" class="form-control" id="number" name="number" value="{{ $certificate->number }}" placeholder="Номер" disabled>
		</div>
	</div>
	<div class="col">
		<div class="form-group">
			<label for="status_id">Status</label>
			<select class="form-control" id="status_id" name="status_id">
				<option value=""></option>
				@foreach($statuses ?? [] as $status)
					<option value="{{ $status->id }}" @if($status->id == $certificate->status_id) selected @endif>{{ $status->name }}</option>
				@endforeach
			</select>
		</div>
	</div>
	<div class="col">
		<div class="form-group">
			<label for="expire_at">Validity</label>
			<div>
				{{ \Carbon\Carbon::parse($certificate->expire_at)->format('Y-m-d H:i') }}
			</div>
		</div>
	</div>
</div>
@if($certificate->product_id)
	<div class="row">
		<div class="col">
			<div class="form-group">
				<label for="file">File</label>
				&nbsp;&nbsp;[
				{{--@if(Storage::disk('private')->exists($certificate->data_json['certificate_file_path'] ?? ''))--}}
					<a href="{{ route('getCertificate', ['uuid' => $certificate->uuid]) }}">download</a>
				{{--@else
					не найден
				@endif--}}
				]
			</div>
		</div>
	</div>
@endif