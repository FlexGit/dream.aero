<input type="hidden" id="id" name="id">
<input type="hidden" id="contractor_id" name="contractor_id">
<input type="hidden" id="amount" name="amount">

<div class="row">
	<div class="col-6">
		<div class="form-group">
			<label for="contractor_search">Client search</label>
			<input type="email" class="form-control" id="contractor_search" name="email" placeholder="Search by Lastname, E-mail, Phone">
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
			<label for="lastname">Lastname</label>
			<input type="text" class="form-control" id="lastname" name="lastname" placeholder="Lastname">
		</div>
	</div>
</div>
<div class="row">
	<div class="col">
		<div class="form-group">
			<label for="product_id">Product</label>
			<select class="form-control js-product" id="product_id" name="product_id">
				<option value="">---</option>
				@foreach($products ?? [] as $productTypeName => $productId)
					<optgroup label="{{ $productTypeName }}">
						@foreach($productId as $product)
							<option value="{{ $product->id }}" data-product_type_id="{{ $product->product_type_id }}" data-currency="{{ $product->currency ? $product->currency->alias : '' }}">{{ $product->name }}</option>
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
				<option value="">---</option>
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
				<option value="">---</option>
				@foreach($promocodes ?? [] as $promocode)
					<option value="{{ $promocode->id }}">{{ $promocode->valueFormatted() }}</option>
				@endforeach
			</select>
		</div>
	</div>
</div>
<div class="row mt-3">
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
			<div id="amount-text">
				<i class="fas fa-dollar-sign" style="font-size: 30px;"></i> <h1 class="d-inline-block">0</h1>
			</div>
		</div>
	</div>
</div>
