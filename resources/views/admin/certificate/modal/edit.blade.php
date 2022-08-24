<input type="hidden" id="id" name="id" value="{{ $certificate->id }}">

<div class="row">
	<div class="col-4">
		<div class="form-group">
			<label for="status_id">Status</label>
			<select class="form-control" id="status_id" name="status_id">
				<option value=""></option>
				@foreach($statuses ?? [] as $status)
					<option value="{{ $status->id }}" @if($status->id == $certificate->status_id) selected @endif>{{ $status->name }}</option>
				@endforeach
			</select>
			<div>
				Validity: {{ \Carbon\Carbon::parse($certificate->expire_at)->format('Y-m-d g:i A') }}
			</div>
		</div>
	</div>
	@if($certificate->product_id)
		<div class="col">
			<div class="form-group">
				<label for="file">File</label>
				<div>
					@if($deal->balance() >= 0)
						[ <a href="{{ route('getCertificate', ['uuid' => $certificate->uuid]) }}">download</a> ]
					@else
						<i>Deal is not paid</i>
					@endif
				</div>
			</div>
		</div>
	@endif
</div>
