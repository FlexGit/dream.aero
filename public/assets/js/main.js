$.ajaxSetup({
	headers: {
		'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	}
});

$(function(){
    $(".ajax-container").on("focusin", function() {
 		$("a.fancybox, a.various").fancybox({
  			'padding': 0
 		});
	});
    
    url = document.location.href;

	/*if(/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) {
		Modile = $('#mainphone').text().replace('+7','8');
		Modile = Modile.replace(/[^0-9]/g,'');
		$('#mainphone').attr('href', 'tel:' + Modile);
		$('#mainphone').removeClass('popup-with-form');
	} else {
		$('#mainphone').attr('href', '#popup-call-back');
	}*/

	$('#delaydiv .cboxClose').click(function() {
		$('#delaydiv').hide("slow");
	});

	$('.noref').click(function() {
		return false;
	});

	$('.ajax_form').append('<input type="text" name="org" value="" class="_org" style="visibility: hidden; height: 0;width: 0;padding: 0;border: none;" />');

	$('.airbo').append('dfdf');

	$('select').niceSelect();

	$objWindow = $(window);

	$('div[data-type="background"]').each(function() {
		var $bgObj = $(this);
		$(window).scroll(function(){
			var yPos =- ($objWindow.scrollTop() / $bgObj.data('speed')) + 200;
			var coords = '100% ' + yPos + 'px';
			if ($(window).width() > 767) {
				$bgObj.css({
					backgroundPosition: coords
				});
			} else {
				$bgObj.css({
					backgroundPosition: '100% ' + '100%'
				});
			}
		});
	});

	$('.go-up').click(function() {
		$('html, body').animate({
			scrollTop: 0
		},900);
		setTimeout(function() {
			$('.shop-show-title').removeClass('viewed');
		},200);
	});

	$('.mobile-burger').click(function() {
		$(this).toggleClass('open');
		$('.main-menu').slideToggle(300);
	});

    $(document).on('click', '.have_promo', function() {
	    $(this).hide();
	    $('.aeroflotbonus').hide();
	    $(".promoblock").show();
	});
	
	$('.popup-close').click(function(e){
		e.preventDefault();
		$.magnificPopup.close();
	});

	var wow = new WOW({
		boxClass:'wow',
		animateClass:'animated',
		offset:0,
		mobile:false,
		live:true,
		scrollContainer:null
	});

	wow.init();

	$(window).on('resize',function(){
		bodyPadding();
	});

	$(window).resize(function(){
		$('#varsL').height($('#varsR').height());
		$('#varsL img').height($('#varsR').height());
	});

	bodyPadding();


	$(document).on('click', '.popup-with-form[data-popup-type]', function(e) {
		popup($(this));
	});

	function popup($el) {
		$.magnificPopup.open({
			items: {
				src: '#popup'
			},
			type: 'inline',
			preloader: false,
			removalDelay: 300,
			mainClass: 'mfp-fade',
			callbacks: {
				open: function () {
					$.magnificPopup.instance.close = function () {
						$.magnificPopup.proto.close.call(this);
					};

					var $popup = $('#popup');

					$popup.hide();

					var url = '';

					switch ($el.data('popup-type')) {
						/*case 'product':
							url = '/modal/certificate-booking/' + $el.data('product-alias');
							break;*/
						case 'callback':
							url = '/modal/callback';
							break;
						case 'review':
							url = '/modal/review';
							break;
						case 'scheme':
							url = '/modal/scheme/' + $el.data('alias');
							break;
						case 'info':
							url = '/modal/info/' + $el.data('alias');
							break;
					}

					$.ajax({
						type: 'GET',
						url: url,
						success: function (result) {
							if (result.status !== 'success') {
								return;
							}

							$popup.find('.popup-container').html(result.html);

							switch ($el.data('popup-type')) {
								case 'callback':
								case 'review':
								case 'info':
									$popup.show();
									break;
								case 'scheme':
									$popup.addClass('popup-map');
									$popup.show();
									break;
								case 'product':
									certificateForm($el.data('product-alias'));
									break;
							}
						}
					});
				}
			}
		});
	}

	$(document).on('click', '.button-tab[data-modal]', function() {
		if ($(this).data('modal') === 'certificate') {
			$('.button-tab[data-modal="certificate"]').removeClass('button-pipaluk-unactive');
			$('.button-tab[data-modal="booking"]').addClass('button-pipaluk-unactive');

			certificateForm($(this).data('product-alias'));
		} else if ($(this).data('modal') === 'booking') {
			$('.button-tab[data-modal="booking"]').removeClass('button-pipaluk-unactive');
			$('.button-tab[data-modal="certificate"]').addClass('button-pipaluk-unactive');

			bookingForm($(this).data('product-alias'), $(this).data('product-type-alias'));
		}
	});

	function certificateForm(productAlias) {
		$.ajax({
			type: 'GET',
			url: '/modal/certificate/' + productAlias,
			success: function (result) {
				console.log(result);
				if (result.status !== 'success') {
					return;
				}

				var $popup = $('#popup');

				$popup.find('.form-container').html(result.html).find('select').niceSelect();

				calcAmount();

				$popup.show();
			}
		});
	}

	$(document).on('change', 'input[name="consent"]', function() {
		var $popup = $(this).closest('.popup, .form'),
			$btn = $popup.find('.js-callback-btn, .js-review-btn, .js-question-btn');

		if ($(this).is(':checked')) {
			$btn.removeClass('button-pipaluk-grey')
				.addClass('button-pipaluk-orange')
				.prop('disabled', false);
		} else {
			$btn.removeClass('button-pipaluk-orange')
				.addClass('button-pipaluk-grey')
				.prop('disabled', true);
		}
	});

	$(document).on('change', 'input[name="rules-consent"], input[name="policy-consent"]', function() {
		var $popup = $(this).closest('.popup, .form'),
			$btn = $popup.find('.js-certificate-btn'),
			$secondCheckbox = ($(this).attr('name') === 'rules-consent') ? $('input[name="policy-consent"]') : $('input[name="rules-consent"]');

		if ($(this).is(':checked') && $secondCheckbox.is(':checked')) {
			$btn.removeClass('button-pipaluk-grey')
				.addClass('button-pipaluk-orange')
				.prop('disabled', false);
		} else {
			$btn.removeClass('button-pipaluk-orange')
				.addClass('button-pipaluk-grey')
				.prop('disabled', true);
		}
	});

	$(document).on('click', '.js-review-btn', function() {
		var $popup = $(this).closest('.popup'),
			name = $popup.find('#name').val(),
			body = $popup.find('#body').val(),
			$alertSuccess = $popup.find('.alert-success'),
			$alertError = $popup.find('.alert-danger');

		var data = {
			'name': name,
			'body': body,
		};

		$.ajax({
			url: '/review/create',
			type: 'POST',
			data: data,
			dataType: 'json',
			success: function (result) {
				$alertSuccess.addClass('hidden');
				$alertError.text('').addClass('hidden');
				$('.field-error').removeClass('field-error');

				if (result.status !== 'success') {
					if (result.reason) {
						$alertError.text(result.reason).removeClass('hidden');
					}
					if (result.errors) {
						const entries = Object.entries(result.errors);
						entries.forEach(function (item, key) {
							var fieldId = item[0];
							$('#' + fieldId).addClass('field-error');
						});
					}
					return;
				}

				$alertSuccess.removeClass('hidden');
				$popup.find('#name, #body').val('');
			}
		});
	});

	$(document).on('click', '.js-question-btn', function() {
		var $popup = $(this).closest('form'),
			name = $popup.find('#name').val(),
			email = $popup.find('#email').val(),
			body = $popup.find('#body').val(),
			$alertSuccess = $popup.find('.alert-success'),
			$alertError = $popup.find('.alert-danger');

		var data = {
			'name': name,
			'email': email,
			'body': body,
		};

		$.ajax({
			url: '/question',
			type: 'POST',
			data: data,
			dataType: 'json',
			success: function (result) {
				$alertSuccess.addClass('hidden');
				$alertError.text('').addClass('hidden');
				$('.border-error').removeClass('border-error');

				if (result.status !== 'success') {
					if (result.reason) {
						$alertError.text(result.reason).removeClass('hidden');
					}
					if (result.errors) {
						const entries = Object.entries(result.errors);
						entries.forEach(function (item, key) {
							var fieldId = item[0];
							$('#' + fieldId).addClass('border-error');
						});
					}
					return;
				}

				$alertSuccess.removeClass('hidden');
				$popup.find('#name, #email, #body').val('');
			}
		});
	});

	$(document).on('click', '.js-callback-btn', function() {
		var $popup = $(this).closest('.popup'),
			name = $popup.find('#name').val(),
			phone = $popup.find('#phone').val(),
			comment = $popup.find('#comment').length ? $popup.find('#comment').val() : '',
			$alertSuccess = $popup.find('.alert-success'),
			$alertError = $popup.find('.alert-danger');

		var data = {
			'name': name,
			'phone': phone,
			'comment': comment,
		};

		$.ajax({
			url: '/callback',
			type: 'POST',
			data: data,
			dataType: 'json',
			success: function (result) {
				$alertSuccess.addClass('hidden');
				$alertError.text('').addClass('hidden');
				$('.field-error').removeClass('field-error');

				if (result.status !== 'success') {
					if (result.reason) {
						$alertError.text(result.reason).removeClass('hidden');
					}
					if (result.errors) {
						const entries = Object.entries(result.errors);
						entries.forEach(function (item, key) {
							var fieldId = item[0];
							$('#' + fieldId).addClass('field-error');
						});
					}
					return;
				}

				$alertSuccess.removeClass('hidden');
				$popup.find('#name, #phone, #comment').val('');
			}
		});
	});
});

function bodyPadding(){
	var header=$('.header');var headerHeight=$(header).outerHeight();$('body').css('padding-top',headerHeight);
}

function calcAmount() {
	var $popup = $('#popup'),
		productId = $popup.find('#product_id').val(),
		promocodeUuid = $popup.find('#promocode_uuid').val(),
		birthday = $popup.find('#birthday').is(':checked') ? 1 : 0,
		weekends = $popup.find('#weekends').is(':checked') ? 1 : 0,
		cityId = $('#city_id').val(),
		$amount = $popup.find('#amount'),
		$amountContainer = $popup.find('.js-amount'),
		$taxContainer = $popup.find('.js-tax'),
		$totalAmountContainer = $popup.find('.js-total-amount'),
		amount = 0;

	var data = {
		product_id: productId,
		promocode_uuid: promocodeUuid,
		birthday: birthday,
		weekends: weekends,
		city_id: cityId,
		source: 'web',
	};

	console.log(data);

	$.ajax({
		type: 'GET',
		url: '/deal/product/calc',
		data: data,
		dataType: 'json',
		success: function(result) {
			console.log(result);

			if (result.status !== 'success') {
				return;
			}

			$('.js-product-name').html(result.product_public_name ? result.product_public_name + (result.product_type_alias !== 'COURSES' ? ' ' + result.product_type_alias + ' ' + result.product_duration + '' : '') : '');

			if (result.amount !== result.baseAmount) {
				amount = '<span class="strikethrough">' + result.currency + result.baseAmount + '</span>' + result.currency + result.amount;
			} else {
				amount = result.currency + result.amount;
			}
			$amount.val(result.amount);
			$amountContainer.html(amount);
			$taxContainer.text(result.currency + result.tax);
			$totalAmountContainer.text(result.currency + result.totalAmount);
		}
	});
}
