<footer class="footer">
	<div class="container">
		<div class="footer-menu">
			<a href="{{ url(Request::session()->get('cityAlias') ?? '/') }}" class="logo">
				@if (App::isLocale('en'))
					<img src="{{ asset('img/logo-eng-footer.png') }}" alt="logo">
				@else
					<img src="{{ asset('img/logo-footer.webp') }}" alt="logo">
				@endif
			</a>
			<div class="social">
				{{--<a href="https://www.facebook.com/dreamaero/"  target="_block"><div class="icon-fb"></div></a>--}}
				<a href="https://t.me/dreamaero" target="_block"><div class="icon-telegram"></div></a>
				<a href="https://www.instagram.com/dream.aero/" target="_block"><div class="icon-inst"></div></a>
				<a href="https://www.youtube.com/channel/UC3huC7ltIlfkNMsz8Jt4qnw" target="_block"><div class="icon-yout"></div></a>
			</div>
		</div>
		<div class="footer-menu">
			<ul>
				<li class="first">
					<a href="{{ url('o-trenazhere') }}">@lang('main.нижнее-меню.о-тренажере')</a>
				</li>
				<li>
					<a href="{{ url('podarit-polet') }}">@lang('main.нижнее-меню.подарить-полет')</a>
				</li>
				<li>
					<a href="{{ url('variantyi-poleta') }}">@lang('main.нижнее-меню.варианты-полета')</a>
				</li>
				<li>
					<a href="{{ url('news') }}">@lang('main.нижнее-меню.новости')</a>
				</li>
				<li>
					<a href="{{ url('instruktazh') }}">@lang('main.нижнее-меню.инструктаж')</a>
				</li>
				<li>
					<a href="{{ url(Request::session()->get('cityAlias') ? Request::session()->get('cityAlias') . '/price' : 'price') }}">@lang('main.нижнее-меню.цены')</a>
				</li>
				<li>
					<a href="{{ url('galereya') }}">@lang('main.нижнее-меню.галерея')</a>
				</li>
				<li>
					<a href="{{ url('reviews') }}">@lang('main.нижнее-меню.отзывы')</a>
				</li>
				<li>
					<a href="{{ url(Request::session()->get('cityAlias') ? Request::session()->get('cityAlias') . '/contacts' : 'contacts') }}">@lang('main.нижнее-меню.контакты')</a>
				</li>
				<li>
					<a href="{{ url('pravila') }}">@lang('main.нижнее-меню.правила')</a>
				</li>
				<li class="last">
					<a href="{{ url('oferta-dreamaero') }}" target=_blank>@lang('main.нижнее-меню.публичная-оферта')</a>
				</li>
			</ul>
			<div class="advert" style="font-size: 13px;">@lang('main.нижнее-меню.копирование-материалов')</div>
		</div>
		<div class="footer-menu">
			<span {{ App::isLocale('en') ? 'style=margin-left:0;' : '' }}>
				<a href="https://www.rossiya-airlines.com/" target="_blank">
					<img style="width: 172px;margin:0 15px 15px 15px;" src="{{ asset('img/logo-white.webp') }}" alt="">
				</a>
				<p style="color: white;margin-top: -5px;font-size: 9px">@lang('main.нижнее-меню.в-партнерстве-с-компанией-россия')</p>
				<p class="advert" style="margin: 0;text-align: right;margin-top: 45px;">
					@lang('main.нижнее-меню.реклама-и-сотрудничество:') <a href="mailto:ads@dream-aero.com">ads@dream-aero.com</a>
				</p>
			</span>
		</div>
	</div>
	<input type="hidden" id="city_id" name="city_id" value="{{ isset($city) ? $city->id : 1 }}">
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

{{--<div id="popup-promo-box" class="overlay">
	<div class="popup popup-promo">
		<a class="close" href="javascript:void(0)" onclick="localStorage.setItem('{{ $promobox->alias }}', true);">&times;</a>
		<div class="content">
			<h2>{{ $promobox->title }}</h2>
			<a href="/news/{{ $promobox->alias }}" onclick="localStorage.setItem('{{ $promobox->alias }}', true);" class="obtain-button button-pipaluk button-pipaluk-orange"><i>подробнее</i></a>
		</div>
	</div>
</div>--}}
