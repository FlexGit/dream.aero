<script>
	(function() {
		var ta = document.createElement('script'); ta.type = 'text/javascript'; ta.async = true;
		ta.src = 'https://analytics.tiktok.com/i18n/pixel/sdk.js?sdkid=BTQQPEORQH54JI5RFPN0';
		var s = document.getElementsByTagName('script')[0];
		s.parentNode.insertBefore(ta, s);
	})();
</script>
<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=AW-11002510393"></script>
<script>
	window.dataLayer = window.dataLayer || [];
	function gtag(){dataLayer.push(arguments);}
	gtag('js', new Date());

	gtag('config', 'AW-11002510393');
</script>
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-246120572-1">
</script>
<script>
	window.dataLayer = window.dataLayer || [];
	function gtag(){dataLayer.push(arguments);}
	gtag('js', new Date());

	gtag('config', 'UA-246120572-1');
</script>
<!-- Facebook Pixel Code -->
<script>
	!function(f,b,e,v,n,t,s)
	{if(f.fbq)return;n=f.fbq=function(){n.callMethod?
		n.callMethod.apply(n,arguments):n.queue.push(arguments)};
		if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
		n.queue=[];t=b.createElement(e);t.async=!0;
		t.src=v;s=b.getElementsByTagName(e)[0];
		s.parentNode.insertBefore(t,s)}(window,document,'script',
		'https://connect.facebook.net/en_US/fbevents.js');
	fbq('init', '2236667376502883');
	fbq('track', 'PageView');
</script>
<noscript>
	<img height="1" width="1"
		 src="https://www.facebook.com/tr?id=2236667376502883&ev=PageView
&noscript=1"/>
</noscript>
<!-- End Facebook Pixel Code -->
<script>
	!function (w, d, t) {
		w.TiktokAnalyticsObject=t;var ttq=w[t]=w[t]||[];ttq.methods=["page","track","identify","instances","debug","on","off","once","ready","alias","group","enableCookie","disableCookie"],ttq.setAndDefer=function(t,e){t[e]=function(){t.push([e].concat(Array.prototype.slice.call(arguments,0)))}};for(var i=0;i<ttq.methods.length;i++)ttq.setAndDefer(ttq,ttq.methods[i]);ttq.instance=function(t){for(var e=ttq._i[t]||[],n=0;n<ttq.methods.length;n++)ttq.setAndDefer(e,ttq.methods[n]);return e},ttq.load=function(e,n){var i="https://analytics.tiktok.com/i18n/pixel/events.js";ttq._i=ttq._i||{},ttq._i[e]=[],ttq._i[e]._u=i,ttq._t=ttq._t||{},ttq._t[e]=+new Date,ttq._o=ttq._o||{},ttq._o[e]=n||{};var o=document.createElement("script");o.type="text/javascript",o.async=!0,o.src=i+"?sdkid="+e+"&lib="+t;var a=document.getElementsByTagName("script")[0];a.parentNode.insertBefore(o,a)};
		ttq.load('C4D45N9U9OSI64ECRUG0');
		ttq.page();
	}(window, document, 'ttq');
</script>
<script type="text/javascript">
	(function (d, w, c) {
		(w[c] = w[c] || []).push(function() {
			try {
				w.yaCounter49890730 = new Ya.Metrika2({
					id:49890730,
					clickmap:true,
					trackLinks:true,
					accurateTrackBounce:true,
					webvisor:true
				});
			} catch(e) { }
		});

		var n = d.getElementsByTagName("script")[0],
			s = d.createElement("script"),
			f = function () { n.parentNode.insertBefore(s, n); };
		s.type = "text/javascript";
		s.async = true;
		s.src = "https://mc.yandex.ru/metrika/tag.js";

		if (w.opera == "[object Opera]") {
			d.addEventListener("DOMContentLoaded", f, false);
		} else { f(); }
	})(document, window, "yandex_metrika_callbacks2");
</script>
<noscript><div><img src="https://mc.yandex.ru/watch/49890730" style="position:absolute; left:-9999px;" alt="" /></div></noscript>

<script src="{{ asset('js/jquery-3.4.1.min.js') }}"></script>
<script src="{{ asset('js/wow.min.js') }}"></script>
<script src="{{ asset('js/jquery.magnific-popup.min.js') }}"></script>
<script src="{{ asset('js/jquery.nice-select.js') }}"></script>
<script src="{{ asset('js/main.js?v=20') }}"></script>
<script src="{{ asset('js/moment-with-locales.min.js') }}"></script>
<script src="{{ asset('js/bootstrap.min.js') }}"></script>
<script src="{{ asset('js/bootstrap-datetimepicker.min.js') }}"></script>

<script>
	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});

	$(window).on("load", function() {
		setInterval(function(){
			$("div").removeClass("conthide");
		}, 1500);
	});

	$(function () {
		var date = new Date(), utc;

		utc = 3;
		date.setHours(date.getHours() + utc, date.getMinutes() + date.getTimezoneOffset());

		$('#datetimepicker').datetimepicker({
			locale: 'ru',
			sideBySide: true,
			stepping: 30,
			minDate: date,
			useCurrent: false,
			disabledHours: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9]
		});

		$(document).on('click', '#city', function(e) {
			e.preventDefault();

			$('.modal .modal-title, .modal .modal-body').empty();

			$.ajax({
				url: '/city/list/ajax',
				type: 'GET',
				dataType: 'json',
				success: function(result) {
					$('#city_modal .modal-body').html(result.html);
				}
			});
		});

		$(document).on('click', '.btn-change', function(e) {
			$container = $(this).closest('.uk-modal-dialog');
			$container.removeClass('gl-default').addClass('gl-change-select');
			$container.find('span.city').text('Select your city');
			$container.find('span.btn-yes').remove();
			$container.find('span.btn-change').remove();
			$container.find('ul.gl-change-list').show(300);
		});

		$(document).on('click', '.btn-yes', function(e) {
			$('#city_modal').modal('hide');
		});

		$(document).on('click', '.js-city', function(e) {
			var pathname = window.location.pathname,
				currentCityAlias = $(this).closest('.uk-modal-dialog').find('[data-current-alias]').data('current-alias');

			$.ajax({
				url: '/city/change',
				type: 'GET',
				dataType: 'json',
				data: {
					alias: $(this).data('alias'),
				},
				success: function(result) {
					if (result.status === 'success') {
						$('#city_modal').modal('hide');
						window.location.href = pathname.replace(currentCityAlias, result.cityAlias);
					}
				}
			});
		});

		var promoboxId = $('#promobox').data('alias'),
			promobox = localStorage.getItem('promobox-' + promoboxId);

		if (!promobox){
			setTimeout(function() {
				$('#promobox').css({'visibility': 'visible', 'opacity': 100});
			}, 500);
		}

		$('.popup .close').on('click', function() {
			$(this).closest('.overlay').css({'visibility': 'hidden', 'opacity': 0});
		});

		$('.js-promobox-btn').on('click', function() {
			localStorage.setItem('promobox-' + $('#promobox').data('alias'), true);
		});
	});
</script>