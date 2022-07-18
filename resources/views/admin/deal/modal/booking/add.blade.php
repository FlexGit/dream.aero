<input type="hidden" id="id" name="id">
<input type="hidden" id="contractor_id" name="contractor_id">
<input type="hidden" id="certificate_uuid" name="certificate_uuid">
<input type="hidden" id="amount" name="amount">
{{--<input type="hidden" id="location_id" name="location_id" value="{{ $locationId }}">--}}
<input type="hidden" id="flight_simulator_id" name="flight_simulator_id" value="{{ $simulatorId }}">
<input type="hidden" id="source" name="source" value="{{ $source ?? '' }}">

@if($source)
	<div class="row">
		<div class="col-3">
			<div class="form-group">
				<div class="custom-control">
					<input type="radio" class="custom-control-input" id="event_type_{{ app('\App\Models\Event')::EVENT_TYPE_DEAL }}" name="event_type" value="{{ app('\App\Models\Event')::EVENT_TYPE_DEAL }}" checked>
					<label class="custom-control-label" for="event_type_{{ app('\App\Models\Event')::EVENT_TYPE_DEAL }}">Сlient flight</label>
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
					<label class="custom-control-label" for="event_type_{{ app('\App\Models\Event')::EVENT_TYPE_CLEANING }}">Cabin cleaning</label>
				</div>
			</div>
		</div>
		<div class="col-2">
			<div class="form-group">
				<div class="custom-control">
					<input type="radio" class="custom-control-input" id="event_type_{{ app('\App\Models\Event')::EVENT_TYPE_TEST_FLIGHT }}" name="event_type" value="{{ app('\App\Models\Event')::EVENT_TYPE_TEST_FLIGHT }}">
					<label class="custom-control-label" for="event_type_{{ app('\App\Models\Event')::EVENT_TYPE_TEST_FLIGHT }}">Test flight</label>
				</div>
			</div>
		</div>
		<div class="col-2">
			<div class="form-group">
				<div class="custom-control">
					<input type="radio" class="custom-control-input" id="event_type_{{ app('\App\Models\Event')::EVENT_TYPE_USER_FLIGHT }}" name="event_type" value="{{ app('\App\Models\Event')::EVENT_TYPE_USER_FLIGHT }}">
					<label class="custom-control-label" for="event_type_{{ app('\App\Models\Event')::EVENT_TYPE_USER_FLIGHT }}">Employee flight</label>
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
			<input type="email" class="form-control" id="contractor_search" name="email" placeholder="Search by full name, e-mail, phone">
			<div class="js-contractor-container hidden">
				<span class="js-contractor"></span> <i class="fas fa-times js-contractor-delete" title="Delete" style="cursor: pointer;color: red;"></i>
			</div>
		</div>
	</div>
	<div class="col-2">
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
	<div class="col-2 text-center">
		<div class="form-group" style="margin-top: 40px;">
			<div class="custom-control custom-switch custom-control">
				<input type="checkbox" id="is_paid" name="is_paid" value="1" class="custom-control-input">
				<label class="custom-control-label font-weight-normal" for="is_paid">Invoice is paid</label>
			</div>
		</div>
	</div>
	<div class="col-2">
		{{--<div class="form-group">
			<label for="roistat_visit">Номер визита Roistat</label>
			<input type="text" class="form-control" id="roistat_visit" name="roistat_visit" placeholder="Номер">
		</div>--}}
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
			<input type="text" class="form-control" id="phone" name="phone" placeholder="+12345678901">
		</div>
	</div>
	<div class="col">
		<div class="form-group">
			<label for="name">Name</label>
			<input type="text" class="form-control" id="name" name="name" placeholder="Name">
		</div>
	</div>
	<div class="col">
		<div class="form-group">
			<label for="lastname">Surname</label>
			<input type="text" class="form-control" id="lastname" name="lastname" placeholder="Surname">
		</div>
	</div>
</div>
<div class="row">
	{{--@if($user->isSuperAdmin())--}}
	{{--<div class="col">
		<div class="form-group">
			<label for="location_id">Location</label>
			<select class="form-control" id="location_id" name="location_id">
				--}}{{--<option value="0">---</option>--}}{{--
				@foreach($cities ?? [] as $city)
					<optgroup label="{{ $city->name }}">
						@foreach($city->locations ?? [] as $location)
							@foreach($location->simulators ?? [] as $simulator)
								<option value="{{ $location->id }}" data-simulator_id="{{ $simulator->id }}" @if($locationId && $locationId == $location->id) selected @endif>{{ $location->name }} ({{ $simulator->name }})</option>
							@endforeach
						@endforeach
					</optgroup>
				@endforeach
			</select>
		</div>
	</div>--}}
	{{--@endif--}}
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
		<div class="form-group">
			<label for="promocode_id">Promocode</label>
			<select class="form-control" id="promocode_id" name="promocode_id">
				<option value="0">---</option>
				@foreach($promocodes ?? [] as $promocode)
					<option value="{{ $promocode->id }}">{{ $promocode->valueFormatted() }}</option>
				@endforeach
			</select>
		</div>
	</div>
</div>
<div class="row">
	<div class="col">
		<div class="form-group">
			<label for="certificate_number">Search by Voucher #</label>
			<input type="text" class="form-control" id="certificate_number" name="certificate_number" placeholder="Voucher #">
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
			<label for="flight_date_at">Flight start</label>
			<div class="d-flex">
				<input type="date" class="form-control" id="flight_date_at" name="flight_date_at" value="{{ $flightAt ? \Carbon\Carbon::parse($flightAt)->format('Y-m-d') : '' }}">
				<input type="time" class="form-control ml-2" id="flight_time_at" name="flight_time_at" value="{{ $flightAt ? \Carbon\Carbon::parse($flightAt)->format('H:i') : '' }}">
			</div>
			<div>
				<input type="hidden" id="is_valid_flight_date" name="is_valid_flight_date">
				<span class="js-event-stop-at"></span>
			</div>
		</div>
	</div>
	<div class="col js-duration hidden">
		<div class="form-group">
			<label for="flight_date_stop_at">Flight end</label>
			<div class="d-flex">
				<input type="date" class="form-control" id="flight_date_stop_at" name="flight_date_stop_at" value="{{ $flightAt ? \Carbon\Carbon::parse($flightAt)->format('Y-m-d') : '' }}">
				<input type="time" class="form-control ml-2" id="flight_time_stop_at" name="flight_time_stop_at">
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
			@if($source)
				<div class="col">
					<div class="form-group">
						<label for="is_repeated_flight">Repeated flight</label>
						<select class="form-control" id="is_repeated_flight" name="is_repeated_flight">
							<option value="0" selected>No</option>
							<option value="1">Yes</option>
						</select>
					</div>
				</div>
				<div class="col">
					<div class="form-group">
						<label for="is_unexpected_flight">Spontaneous flight</label>
						<select class="form-control" id="is_unexpected_flight" name="is_unexpected_flight">
							<option value="0" selected>No</option>
							<option value="1">Yes</option>
						</select>
					</div>
				</div>
			@endif
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
			<div class="custom-control custom-switch custom-control-inline">
				<input type="checkbox" id="is_free" name="is_free" value="1" class="custom-control-input">
				<label class="custom-control-label font-weight-normal" for="is_free">Free</label>
			</div>
			<div id="amount-text">
				<h1 class="d-inline-block">0</h1>
				<i class="fas fa-dollar-sign" style="font-size: 25px;"></i>
			</div>
		</div>
	</div>
</div>
