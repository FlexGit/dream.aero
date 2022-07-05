<input type="hidden" id="id" name="id" value="{{ $deal->id }}">
<input type="hidden" id="city_id" name="city_id" value="{{ $deal->city_id }}">
<input type="hidden" id="contractor_id" name="contractor_id" value="{{ $deal->contractor_id }}">

@if($deal->contractor)
	<div class="row">
		<div class="col">
			<div class="form-group">
				@if($deal->contractor->email == app('\App\Models\Contractor')::ANONYM_EMAIL || $user->isSuperAdmin())
					<label for="contractor_search">Client search</label>
					<input type="email" class="form-control" id="contractor_search" value="{{ $deal->contractor ? $deal->contractor->email : '' }}" placeholder="Search by full name, e-mail, phone" {{ $deal->contractor ? 'disabled' : '' }}>
					<div class="js-contractor-container {{ $deal->contractor ? '' : 'hidden' }}">
						<span class="js-contractor">Linked client: {{ $deal->contractor->fio() . ' [' . ($deal->contractor->email ? $deal->contractor->email . ', ' : '') . ($deal->contractor->phone ? $deal->contractor->phone . ', ' : '') . ($deal->contractor->city ? $deal->contractor->city->name : '') . ']' }}</span> <i class="fas fa-times js-contractor-delete" title="Delete" style="cursor: pointer;color: red;"></i>
					</div>
				@else
					<label>Client</label>
					<div>
						{{ $deal->contractor->fio() . ' [' . ($deal->contractor->email ? $deal->contractor->email . ', ' : '') . ($deal->contractor->phone ? $deal->contractor->phone . ', ' : '') . ($deal->contractor->city ? $deal->contractor->city->name : '') . ']' }}
					</div>
				@endif
			</div>
		</div>
	</div>
@endif
<div class="row">
	<div class="col">
		<div class="form-group">
			<label for="number">Deal number</label>
			<input type="text" class="form-control" placeholder="Number" value="{{ $deal->number }}" disabled>
		</div>
	</div>
	<div class="col">
		<div class="form-group">
			<label for="status_id">Deal status</label>
			<select class="form-control" id="status_id" name="status_id">
				<option></option>
				@foreach($statuses ?? [] as $status)
					<option value="{{ $status->id }}" @if($status->id === $deal->status_id) selected @endif>{{ $status->name }}</option>
				@endforeach
			</select>
		</div>
	</div>
	<div class="col">
		{{--<div class="form-group">
			<label for="roistat_visit">Номер визита Roistat</label>
			<input type="text" class="form-control" id="roistat_visit" name="roistat_visit" value="{{ $deal->roistat }}" placeholder="Номер" disabled>
		</div>--}}
	</div>
</div>
<div class="row">
	<div class="col">
		<div class="form-group">
			<label for="name">Conact person</label>
			<input type="text" class="form-control" id="name" name="name" value="{{ $deal->name }}" placeholder="Name">
		</div>
	</div>
	<div class="col">
		<div class="form-group">
			<label for="email">Contact E-mail</label>
			<input type="email" class="form-control" id="email" name="email" value="{{ $deal->email }}" placeholder="E-mail">
		</div>
	</div>
	<div class="col">
		<div class="form-group">
			<label for="phone">Contact phone number</label>
			<input type="text" class="form-control" id="phone" name="phone" value="{{ $deal->phone }}" placeholder="+12345678901">
		</div>
	</div>
</div>
{{--<div class="row">
	<div class="col-8">
		<label for="comment">Comment</label>
		<textarea class="form-control" id="comment" name="comment" rows="2">{{ isset($deal->data_json['comment']) ? $deal->data_json['comment'] : '' }}</textarea>
	</div>
</div>--}}
