<footer class="footer">
	<div class="container">
		<div class="footer-menu">
			<a href="{{ url($city->alias ?? '/') }}" class="logo">
				<img src="{{ asset('img/logo-eng-footer.png') }}" alt="logo">
			</a>
			<div class="social" style="display: block;vertical-align: bottom;margin: 25px 0 0 0;text-align: center;padding-bottom: 5px;">
				@if ($city->alias == app('\App\Models\City')::DC_ALIAS)
					<a href="https://www.facebook.com/dreamaerous/" target="_blank"><img src="{{ asset('img/fb.png') }}" alt="logo"></a>
					<a href="https://www.instagram.com/dreamaero.us" target="_blank"><img src="{{ asset('img/inst.png') }}" alt="logo"></a>
					<a href="https://www.youtube.com/channel/UCSg-5Jw7aeZdqPOKeGC3ctA" target="_blank"><img src="{{ asset('img/you.png') }}" alt="logo"></a>
					<a href="https://www.snapchat.com/add/dreamaerous" target="_blank"><img src="{{ asset('img/snapchat.png') }}" alt="logo"></a>
					<a href="https://twitter.com/dream_aero" target="_blank"><img src="{{ asset('img/twitter.png') }}" alt="logo"></a>
				@endif
				@if ($city->alias == app('\App\Models\City')::UAE_ALIAS)
					<a href="https://www.facebook.com/dreamaerouae/" target="_blank"><img src="{{ asset('img/fb.png') }}" alt="logo"></a>
					<a href="https://www.instagram.com/dreamaerouae/" target="_blank"><img src="{{ asset('img/inst.png') }}" alt="logo"></a>
					<a href="https://www.youtube.com/channel/UCSg-5Jw7aeZdqPOKeGC3ctA" target="_blank"><img src="{{ asset('img/you.png') }}" alt="logo"></a>
				@endif
			</div>
		</div>
		<div class="footer-menu">
			<ul>
				<li class="first">
					<a href="{{ url($city->alias . '/about-simulator') }}">ABOUT THE SIMULATOR</a>
				</li>
				<li>
					<a href="{{ url($city->alias . '/gift-sertificates') }}">GIFT VOUCHERS</a>
				</li>
				<li>
					<a href="{{ url($city->alias . '/flight-options') }}">FLIGHT ROUTE OPTIONS</a>
				</li>
				<li>
					<a href="{{ url($city->alias . '/news') }}">NEWS</a>
				</li>
				@if ($city->alias == app('\App\Models\City')::DC_ALIAS)
					<li>
						<a href="{{ url($city->alias . '/private-events') }}">PRIVATE EVENTS</a>
					</li>
				@endif
				@if ($city->alias == app('\App\Models\City')::UAE_ALIAS)
					<li>
						<a href="{{ url($city->alias . '/flight-briefing') }}">FLIGHT BRIEFING</a>
					</li>
				@endif
				<li>
					<a href="{{ url($city->alias . '/prices') }}">PRICES</a>
				</li>
				<li>
					<a href="{{ url($city->alias . '/gallery') }}">GALLERY</a>
				</li>
				<li>
					<a href="{{ url($city->alias . '/reviews') }}">REVIEWS</a>
				</li>
				<li>
					<a href="{{ url($city->alias . '/contacts') }}">CONTACT US</a>
				</li>
				<li>
					<a href="{{ url($city->alias . '/rules') }}">TERMS AND CONDITIONS</a>
				</li>
				<li class="last">
					<a href="{{ url($city->alias . '/privacy-policy') }}">PRIVACY AND COOKIE POLICY</a>
				</li>
			</ul>
		</div>
		<div class="footer-menu">
			<div id="payments" style="color: #fff;float: right;font-size: 10px;letter-spacing: .5px;margin-left: 25px;display: inline-block;">
				<p>We accept payments</p>
				<p>
					<img src="{{ asset('img/visa-mastercard.png') }}" width="90px" alt="">
					<img src="{{ asset('img/verified-by-visa-png.png') }}" width="120px" alt="">
				</p>
			</div>
		</div>
	</div>
	<input type="hidden" id="city_id" name="city_id" value="{{ $city->id }}">
</footer>

<div class="go-up"></div>

<div class="modal fade" id="city_modal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
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
</div>

<div class="mfp-hide popup ajax_form" id="popup" style="display: none;">
	<button title="Close (Esc)" type="button" class="mfp-close">×</button>
	<div class="popup-container"></div>
</div>