<input type="hidden" id="id" name="id">
<input type="hidden" id="contractor_id" name="contractor_id">
<input type="hidden" id="certificate_uuid" name="certificate_uuid">
<input type="hidden" id="amount" name="amount">
{{--<input type="hidden" id="location_id" name="location_id" value="{{ $locationId }}">--}}
<input type="hidden" id="flight_simulator_id" name="flight_simulator_id" value="{{ $simulatorId }}">
<input type="hidden" id="source" name="source" value="{{ $source ?? '' }}">

@if($source)
	<div class="row">
		<div class="col-3 text-nowrap">
			<div class="form-group">
				<div class="custom-control">
					<input type="radio" class="custom-control-input" id="event_type_{{ app('\App\Models\Event')::EVENT_TYPE_DEAL }}" name="event_type" value="{{ app('\App\Models\Event')::EVENT_TYPE_DEAL }}" checked>
					<label class="custom-control-label" for="event_type_{{ app('\App\Models\Event')::EVENT_TYPE_DEAL }}">Сlient Flight</label>
				</div>
			</div>
		</div>
		<div class="col-2">
			<div class="form-group">
				<div class="custom-control">
					<input type="radio" class="custom-control-input" id="event_type_{{ app('\App\Models\Event')::EVENT_TYPE_BREAK }}" name="event_type" value="{{ app('\App\Models\Event')::EVENT_TYPE_BREAK }}">
					<label class="custom-control-label" for="event_type_{{ app('\App\Models\Event')::EVENT_TYPE_BREAK }}">Break</label>
				</div>
			</div>
		</div>
		<div class="col-2">
			<div class="form-group">
				<div class="custom-control">
					<input type="radio" class="custom-control-input" id="event_type_{{ app('\App\Models\Event')::EVENT_TYPE_CLEANING }}" name="event_type" value="{{ app('\App\Models\Event')::EVENT_TYPE_CLEANING }}">
					<label class="custom-control-label" for="event_type_{{ app('\App\Models\Event')::EVENT_TYPE_CLEANING }}">Cleaning</label>
				</div>
			</div>
		</div>
		<div class="col-2 text-nowrap">
			<div class="form-group">
				<div class="custom-control">
					<input type="radio" class="custom-control-input" id="event_type_{{ app('\App\Models\Event')::EVENT_TYPE_TEST_FLIGHT }}" name="event_type" value="{{ app('\App\Models\Event')::EVENT_TYPE_TEST_FLIGHT }}">
					<label class="custom-control-label" for="event_type_{{ app('\App\Models\Event')::EVENT_TYPE_TEST_FLIGHT }}">Test Flight</label>
				</div>
			</div>
		</div>
		<div class="col-2 text-nowrap">
			<div class="form-group">
				<div class="custom-control">
					<input type="radio" class="custom-control-input" id="event_type_{{ app('\App\Models\Event')::EVENT_TYPE_USER_FLIGHT }}" name="event_type" value="{{ app('\App\Models\Event')::EVENT_TYPE_USER_FLIGHT }}">
					<label class="custom-control-label" for="event_type_{{ app('\App\Models\Event')::EVENT_TYPE_USER_FLIGHT }}">Employee Flight</label>
				</div>
			</div>
		</div>
	</div>
	<hr>
@else
	<input type="hidden" name="event_type" value="{{ app('\App\Models\Event')::EVENT_TYPE_DEAL }}">
@endif
<div class="row">
	<div class="col-6">
		<div class="form-group">
			<label for="contractor_search">Client search</label>
			<div class="d-flex">
				<input type="email" class="form-control" id="contractor_search" name="email" placeholder="Email or Phone number">
				<button type="button" class="btn btn-secondary btn-sm js-contractor-search">Link</button>
			</div>
			<div class="js-contractor-container hidden">
				<span class="js-contractor"></span> <i class="fas fa-times js-contractor-delete" title="Delete" style="cursor: pointer;color: red;"></i>
			</div>
		</div>
	</div>
	<div class="col-3">
		<div class="form-group">
			<label for="payment_method_id">Payment method</label>
			<select class="form-control" id="payment_method_id" name="payment_method_id">
				<option value="">---</option>
				@foreach($paymentMethods ?? [] as $paymentMethod)
					<option value="{{ $paymentMethod->id }}" data-alias="{{ $paymentMethod->alias }}">{{ $paymentMethod->name }}</option>
				@endforeach
			</select>
		</div>
	</div>
	<div class="col-3 text-center">
		<div class="form-group" style="margin-top: 40px;">
			<div class="custom-control custom-switch custom-control">
				<input type="checkbox" id="is_paid" name="is_paid" value="1" class="custom-control-input">
				<label class="custom-control-label font-weight-normal" for="is_paid">Invoice is paid</label>
			</div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col">
		<div class="form-group">
			<label for="email">E-mail</label>
			<input type="email" class="form-control" id="email" name="email" placeholder="">
		</div>
	</div>
	<div class="col">
		<div class="form-group">
			<label for="phone">Phone</label>
			<input type="text" class="form-control" id="phone" name="phone" placeholder="">
		</div>
	</div>
	<div class="col">
		<div class="form-group">
			<label for="name">Name</label>
			<input type="text" class="form-control" id="name" name="name" placeholder="">
		</div>
	</div>
	<div class="col">
		<div class="form-group">
			<label for="lastname">Lastname</label>
			<input type="text" class="form-control" id="lastname" name="lastname" placeholder="">
		</div>
	</div>
</div>
<div class="row">
	<div class="col">
		<div class="form-group">
			<label for="product_id">Product</label>
			<select class="form-control js-product" id="product_id" name="product_id">
				<option value="0">---</option>
				@foreach($products ?? [] as $productTypeName => $productId)
					<optgroup label="{{ $productTypeName }}">
						@foreach($productId as $product)
							<option value="{{ $product->id }}" data-product_type_id="{{ $product->product_type_id }}" data-duration="{{ $product->duration }}">{{ $product->name }}</option>
						@endforeach
					</optgroup>
				@endforeach
			</select>
		</div>
	</div>
	<div class="col">
		<div class="form-group">
			<label for="promo_id">Promo</label>
			<select class="form-control" id="promo_id" name="promo_id">
				<option value="0">---</option>
				@foreach($promos ?? [] as $promo)
					<option value="{{ $promo->id }}">{{ $promo->valueFormatted() }}</option>
				@endforeach
			</select>
		</div>
	</div>
	<div class="col">
		@if($promocodes->count())
			<div class="form-group">
				<label for="promocode_id">Promocode</label>
				<select class="form-control" id="promocode_id" name="promocode_id">
					<option value="0">---</option>
					@foreach($promocodes ?? [] as $promocode)
						<option value="{{ $promocode->id }}">{{ $promocode->valueFormatted() }}</option>
					@endforeach
				</select>
			</div>
		@endif
	</div>
</div>
<div class="row">
	<div class="col">
		<div class="form-group">
			<label for="certificate_number">Voucher</label>
			<div class="d-flex">
				<input type="text" class="form-control" id="certificate_number" name="certificate_number" placeholder="Voucher number">
				<button type="button" class="btn btn-secondary btn-sm js-certificate-search">Link</button>
			</div>
			<div class="js-certificate-container hidden">
				<span class="js-certificate"></span> <i class="fas fa-times js-certificate-delete" title="Delete" style="cursor: pointer;color: red;"></i>
				<div class="custom-control custom-switch custom-control js-is-indefinitely hidden">
					<input type="checkbox" id="is_indefinitely" name="is_indefinitely" value="1" class="custom-control-input">
					<label class="custom-control-label font-weight-normal" for="is_indefinitely">Ignore validity</label>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col">
		<div class="form-group">
			<label for="start_date_at">Flight start</label>
			<div class="d-flex">
				<input type="date" class="form-control" id="start_date_at" name="start_date_at" value="{{ $flightAt ? \Carbon\Carbon::parse($flightAt)->format('Y-m-d') : '' }}">
				<input type="time" class="form-control ml-2" id="start_time_at" name="start_time_at" value="{{ $flightAt ? \Carbon\Carbon::parse($flightAt)->format('H:i') : '' }}">
			</div>
			<div>
				<input type="hidden" id="is_valid_flight_date" name="is_valid_flight_date">
				<span class="js-event-stop-at"></span>
			</div>
		</div>
	</div>
	<div class="col js-duration hidden">
		<div class="form-group">
			<label for="stop_date_at">Flight stop</label>
			<div class="d-flex">
				<input type="date" class="form-control" id="stop_date_at" name="stop_date_at" value="{{ $flightAt ? \Carbon\Carbon::parse($flightAt)->format('Y-m-d') : '' }}">
				<input type="time" class="form-control ml-2" id="stop_time_at" name="stop_time_at">
			</div>
		</div>
	</div>
	<div class="col js-employee hidden">
		<div class="form-group">
			<label for="employee_id">Employee</label>
			<select class="form-control" id="employee_id" name="employee_id">
				<option value="0">---</option>
				@foreach($employees as $employee)
					<option value="{{ $employee->id }}">{{ $employee->fio() }}</option>
				@endforeach
			</select>
		</div>
	</div>
	<div class="col js-pilot hidden">
		<div class="form-group">
			<label for="pilot_id">Pilot</label>
			<select class="form-control" id="pilot_id" name="pilot_id">
				<option value="0">---</option>
				@foreach($pilots as $pilot)
					<option value="{{ $pilot->id }}">{{ $pilot->fio() }}</option>
				@endforeach
			</select>
		</div>
	</div>
	<div class="col">
		<div class="row">
			<div class="col">
				<div class="form-group">
					<label for="extra_time">Extra time</label>
					<select class="form-control" id="extra_time" name="extra_time">
						<option value="0">---</option>
						<option value="15">15 min</option>
					</select>
				</div>
			</div>
			{{--@if($source)--}}
				<div class="col">
					<div class="form-group">
						<label for="is_repeated_flight">Repeated</label>
						<select class="form-control" id="is_repeated_flight" name="is_repeated_flight">
							<option value="0" selected>No</option>
							<option value="1">Yes</option>
						</select>
					</div>
				</div>
				<div class="col">
					<div class="form-group">
						<label for="is_unexpected_flight">Spontaneous</label>
						<select class="form-control" id="is_unexpected_flight" name="is_unexpected_flight">
							<option value="0" selected>No</option>
							<option value="1">Yes</option>
						</select>
					</div>
				</div>
			{{--@endif--}}
		</div>
	</div>
</div>
<div class="row">
	<div class="col-8">
		<label for="comment">Comment</label>
		<textarea class="form-control" id="comment" name="comment" rows="2"></textarea>
	</div>
	<div class="col-4 text-right">
		<div class="form-group mt-4">
			{{--<div class="custom-control custom-switch custom-control-inline">
				<input type="checkbox" id="is_free" name="is_free" value="1" class="custom-control-input">
				<label class="custom-control-label font-weight-normal" for="is_free">Free</label>
			</div>--}}
			<div id="amount-text" style="font-size: 30px;">
				Subtotal: <i class="fas fa-dollar-sign"></i> <span class="d-inline-block">0</span>
			</div>
			<div id="tax-text" style="font-size: 18px;">
				Tax: <i class="fas fa-dollar-sign"></i> <span class="d-inline-block">0</span>
			</div>
			<div id="total-amount-text" style="font-size: 18px;">
				Total: <i class="fas fa-dollar-sign"></i> <span class="d-inline-block">0</span>
			</div>
		</div>
	</div>
</div>
