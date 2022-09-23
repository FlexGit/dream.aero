<footer class="footer">
	<div class="container">
		<div class="footer-menu">
			<a href="{{ url('/') }}" class="logo">
				<img src="{{ asset('img/' . Session::get('domain') . '/logo-footer.webp') }}" alt="logo">
			</a>
		</div>
		<div class="footer-menu">
			<ul>
				<li class="first">
					<a href="{{ url('about-simulator') }}">ABOUT THE SIMULATOR</a>
				</li>
				<li>
					<a href="{{ url('gift-sertificates') }}">GIFT VOUCHERS</a>
				</li>
				<li>
					<a href="{{ url('flight-options') }}">FLIGHT ROUTE OPTIONS</a>
				</li>
				<li>
					<a href="{{ url('prices') }}">PRICES</a>
				</li>
				<li>
					<a href="{{ url('contacts') }}">CONTACT US</a>
				</li>
			</ul>
		</div>
		<div class="footer-menu">
			<div id="payments" style="color: #fff;float: right;font-size: 10px;letter-spacing: .5px;margin-left: 25px;display: inline-block;">
				<p>We accept payments</p>
				<p>
					<img src="{{ asset('img/' . Session::get('domain') . '/visa-mastercard.png') }}" width="90px" alt="">
					<img src="{{ asset('img/' . Session::get('domain') . '/verified-by-visa-png.png') }}" width="120px" alt="">
				</p>
			</div>
		</div>
	</div>
	{{--<input type="hidden" id="city_id" name="city_id" value="{{ Request::session()->get('cityId') }}">--}}
</footer>

<div class="go-up"></div>

{{--<div class="modal fade" id="city_modal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
			</div>
		</div>
	</div>
</div>--}}

<div class="mfp-hide popup ajax_form" id="popup" style="display: none;">
	<button title="Close (Esc)" type="button" class="mfp-close">×</button>
	<div class="popup-container"></div>
</div>

{{--@if(isset($promobox))
	@include('includes.promobox')
@endif--}}
