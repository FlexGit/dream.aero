<input type="hidden" id="id" name="id" value="{{ $deal->id }}">
<input type="hidden" id="city_id" name="city_id" value="{{ $deal->city_id }}">
<input type="hidden" id="contractor_id" name="contractor_id" value="{{ $deal->contractor_id }}">
<input type="hidden" id="amount" name="amount" value="{{ $deal->amount }}">
<input type="hidden" id="tax" name="tax" value="{{ $deal->tax }}">
<input type="hidden" id="total_amount" name="total_amount" value="{{ $deal->total_amount }}">

@if($deal->contractor)
	<div class="row">
		<div class="col-8">
			<div class="form-group">
				@if($deal->contractor->email == app('\App\Models\Contractor')::ANONYM_EMAIL || $user->isSuperAdmin())
					<label for="contractor_search">Client search</label>
					<input type="email" class="form-control" id="contractor_search" value="{{ $deal->contractor ? $deal->contractor->email : '' }}" placeholder="Search by full name, e-mail, phone" {{ $deal->contractor ? 'disabled' : '' }}>
					<div class="js-contractor-container {{ $deal->contractor ? '' : 'hidden' }}">
						<span class="js-contractor">Linked client: {{ $deal->contractor->fio() . ' [' . ($deal->contractor->email ? $deal->contractor->email . ', ' : '') . ($deal->contractor->phone ? $deal->contractor->phone . ', ' : '') . ']' }}</span> <i class="fas fa-times js-contractor-delete" title="Delete" style="cursor: pointer;color: red;"></i>
					</div>
				@else
					<label>Client</label>
					<div>
						{{ $deal->contractor->fio() . ' [' . ($deal->contractor->email ? $deal->contractor->email . ', ' : '') . ($deal->contractor->phone ? $deal->contractor->phone . ', ' : '') . ($deal->contractor->city ? $deal->contractor->city->name : '') . ']' }}
					</div>
				@endif
			</div>
		</div>
		<div class="col-4">
			<div class="form-group">
				<label for="status_id">Status</label>
				<select class="form-control" id="status_id" name="status_id">
					<option></option>
					@foreach($statuses ?? [] as $status)
						<option value="{{ $status->id }}" @if($status->id === $deal->status_id) selected @endif>{{ $status->name }}</option>
					@endforeach
				</select>
			</div>
		</div>
	</div>
@endif
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
<div class="row">
	<div class="col-4">
		<div class="form-group">
			<label for="product_id">Product</label>
			<select class="form-control js-product" id="product_id" name="product_id">
				<option></option>
				@foreach($products ?? [] as $productTypeName => $productId)
					<optgroup label="{{ $productTypeName }}">
						@foreach($productId as $product)
							<option value="{{ $product->id }}" data-product_type_id="{{ $product->product_type_id }}" data-duration="{{ $product->duration }}" @if($product->id == $deal->product_id) selected @endif>{{ $product->name }}</option>
						@endforeach
					</optgroup>
				@endforeach
			</select>
		</div>
	</div>
	@if($deal->is_certificate_purchase || (!$deal->is_certificate_purchase && $deal->location_id))
		<div class="col-4">
			<div class="form-group">
				<label for="promo_id">Promo</label>
				<select class="form-control" id="promo_id" name="promo_id">
					<option value=""></option>
					@foreach($promos ?? [] as $promo)
						<option value="{{ $promo->id }}" @if($promo->id == $deal->promo_id) selected @endif>{{ $promo->valueFormatted() }}{{ !$promo->is_active ? ' - not active' : '' }}</option>
					@endforeach
				</select>
			</div>
		</div>
		<div class="col-4">
			@if($promocodes->count())
				<div class="form-group">
					<label for="promocode_id">Promocode</label>
					<select class="form-control" id="promocode_id" name="promocode_id">
						<option value=""></option>
						@foreach($promocodes ?? [] as $promocode)
							<option value="{{ $promocode->id }}" @if($promocode->id == $deal->promocode_id) selected @endif>{{ $promocode->valueFormatted() }}</option>
						@endforeach
					</select>
				</div>
			@endif
		</div>
	@endif
</div>
@if(!$deal->is_certificate_purchase && $deal->location_id)
	<div class="row">
		<div class="col">
			<div class="form-group">
				<label>Flight start</label>
				<div class="d-flex">
					<input type="date" class="form-control" name="start_at_date" value="{{ ($deal->event && $deal->event->start_at) ? \Carbon\Carbon::parse($deal->event->start_at)->format('Y-m-d') : '' }}" placeholder="">
					<input type="time" class="form-control ml-2" name="start_at_time" value="{{ ($deal->event && $deal->event->start_at) ? \Carbon\Carbon::parse($deal->event->start_at)->format('H:i') : '' }}" placeholder="">
				</div>
			</div>
		</div>
		<div class="col">
			<div class="form-group">
				<label for="extra_time">Extra time</label>
				<select class="form-control" id="extra_time" name="extra_time">
					<option value="0" @if($deal->event && !$deal->event->extra_time) selected @endif>---</option>
					<option value="15" @if($deal->event && $deal->event->extra_time == 15) selected @endif>15 min</option>
				</select>
			</div>
		</div>
		<div class="col">
			<div class="form-group">
				<label for="is_repeated_flight">Repeated flight</label>
				<select class="form-control" id="is_repeated_flight" name="is_repeated_flight">
					<option value="0" @if($deal->event && !$deal->event->is_repeated_flight) selected @endif>No</option>
					<option value="1" @if($deal->event && $deal->event->is_repeated_flight) selected @endif>Yes</option>
				</select>
			</div>
		</div>
		<div class="col">
			<div class="form-group">
				<label for="is_unexpected_flight">Spontaneous flight</label>
				<select class="form-control" id="is_unexpected_flight" name="is_unexpected_flight">
					<option value="0" @if($deal->event && !$deal->event->is_unexpected_flight) selected @endif>No</option>
					<option value="1" @if($deal->event && $deal->event->is_unexpected_flight) selected @endif>Yes</option>
				</select>
			</div>
		</div>
	</div>
@endif
<div class="row">
	<div class="col-8">
		{{--<div class="pl-2 pr-2" style="line-height: 1.1em;">
			@foreach($comments ?? [] as $comment)
				<div class="d-flex justify-content-between mt-2 mb-2 pt-2 js-comment-container">
					<div style="width: 93%;">
						<div class="mb-0">
							<span class="comment-text" data-comment-id="{{ $comment['id'] }}">{{ $comment['name'] }}</span>
						</div>
						<div class="font-italic font-weight-normal mt-1 mb-0" style="line-height: 0.9em;border-top: 1px solid #bbb;">
							<small class="user-info" data-comment-id="{{ $comment['id'] }}">{{ $comment['wasUpdated'] }}: {{ $comment['user'] ?? '' }}, {{ $comment['date'] }}</small>
						</div>
					</div>
					<div class="d-flex">
						<div>
							<i class="far fa-edit js-comment-edit" data-comment-id="{{ $comment['id'] }}" title="Edit"></i>
						</div>
						<div class="ml-2">
							<i class="fas fa-trash-alt js-comment-remove" data-comment-id="{{ $comment['id'] }}" data-confirm-text="Are you sure?" title="Delete"></i>
						</div>
					</div>
				</div>
			@endforeach
		</div>--}}
		<label for="comment">Comment</label>
		<textarea class="form-control" id="comment" name="comment" rows="2">{{ isset($deal->data_json['comment']) ? $deal->data_json['comment'] : '' }}</textarea>
	</div>
	<div class="col-4 text-right">
		<div class="form-group">
			<div id="amount-text" style="font-size: 30px;">
				Subtotal: <i class="fas fa-dollar-sign"></i> <span class="d-inline-block">{{ $deal->amount }}</span>
			</div>
			<div id="tax-text" style="font-size: 18px;">
				Tax: <i class="fas fa-dollar-sign"></i> <span class="d-inline-block">{{ $deal->tax }}</span>
			</div>
			<div id="total-amount-text" style="font-size: 18px;">
				Total: <i class="fas fa-dollar-sign"></i> <span class="d-inline-block">{{ $deal->total_amount }}</span>
			</div>
		</div>
	</div>
</div>
