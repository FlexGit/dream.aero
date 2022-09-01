$(function() {
	$.datetimepicker.setLocale('en', {
		year: 'numeric',
		month: '2-digit',
		day: '2-digit'
	});

	$(document).on('click', 'ul.tabs__caption li:not(.active)', function(e) {
		$(this).addClass('active').siblings().removeClass('active').closest('div.tabs').find('div.tabs__content').removeClass('active').eq($(this).index()).addClass('active');
	});

	$(document).on('click', 'ul.tabs2__caption li:not(.active)', function(e) {
		$(this).addClass('active').siblings().removeClass('active').closest('div.tabs2').find('div.tabs2__content').removeClass('active').eq($(this).index()).addClass('active');
	});

	$(document).on('mouseover', '.block-price', function(e) {
		$(this).find('.h4plat').show();
	});

	$(document).on('mouseleave', '.block-price', function(e) {
		$(this).find('.h4plat').hide();
	});

	$('.popup-with-form[data-modal]').magnificPopup({
		type: 'inline',
		preloader: false,
		removalDelay: 300,
		mainClass: 'mfp-fade',
		callbacks: {
			open: function() {
				var $popup = $('#popup'),
					mp = $.magnificPopup.instance,
					t = $(mp.currItem.el[0]);

				mp.close = function() {
					$('form')[0].reset();
					$popup.hide();
					$.magnificPopup.proto.close.call(this);
				};

				$.ajax({
					type: 'POST',
					url: '/modal/certificate',
					data: {
						product_alias: (t.data('product-alias') !== undefined) ? t.data('product-alias') : '',
					},
					success: function (result) {
						//console.log(result);
						if (result.status !== 'success') {
							return;
						}

						$popup.find('.popup-container').html(result.html).find('select').niceSelect();
						$popup.show();

						calcAmount();

						tippy('[data-tippy-content]', {
							placement: 'right',
						});
					}
				});
			}
		}
	});

	$(document).on('change', '#has_promocode', function() {
		var $popup = $(this).closest('.popup');

		if ($(this).is(':checked')) {
			$popup.find('#promocode').show().focus();
			$popup.find('.js-promocode-btn').show();
			$popup.find('.promocode_note').show();
		} else {
			$('.js-promocode-remove').trigger('click');
			$popup.find('#promocode').hide();
			$popup.find('.js-promocode-btn').hide();
			$popup.find('.promocode_note').hide();
		}
	});

	$(document).on('change', '#birthday, #weekends', function() {
		calcAmount();
	});


	$(document).on('click', '.js-promocode-btn', function() {
		var $promocodeApplyBtn = $(this),
			$promocodeContainer = $promocodeApplyBtn.closest('.promocode_container'),
			$promocode = $promocodeContainer.find('#promocode'),
			$promocodeRemoveBtn = $promocodeContainer.find('.js-promocode-remove'),
			$fieldset = $promocodeApplyBtn.closest('fieldset'),
			$product = $fieldset.find('#product_id'),
			$errorMsg = $promocodeContainer.find('.text-error'),
			$successMsg = $promocodeContainer.find('.text-success'),
			$promocodeUuid = $fieldset.find('#promocode_uuid'),
			$locationSimulator = $('input[name="locationSimulator"]:checked'),
			locationId = $locationSimulator.data('location-id'),
			simulatorId = $locationSimulator.data('simulator-id');

		$errorMsg.remove();
		$successMsg.remove();

		if (!$promocode.val().length) return;
		if ($product.val() === null) {
			$promocode.after('<p class="text-error text-small">' + $promocode.data('no-product-error') + '</p>');
			return;
		}

		$.ajax({
			url: '/promocode/verify',
			type: 'POST',
			data: {
				'promocode': $promocode.val(),
				'location_id': locationId,
				'simulator_id': simulatorId,
			},
			dataType: 'json',
			success: function (result) {
				if (result.status !== 'success') {
					$promocode.after('<p class="text-error text-small">' + result.reason + '</p>');
					return;
				}

				$promocode.after('<p class="text-success text-small">' + result.message + '</p>');
				$promocodeUuid.val(result.uuid);
				$promocode.attr('disabled', true);
				$promocodeApplyBtn.hide();
				$promocodeRemoveBtn.show();

				calcAmount();
			}
		});
	});

	$(document).on('click', '.js-promocode-remove', function() {
		var $promocodeRemoveBtn = $(this),
			$promocodeContainer = $promocodeRemoveBtn.closest('.promocode_container'),
			$promocodeApplyBtn = $promocodeContainer.find('.js-promocode-btn'),
			$promocode = $promocodeContainer.find('#promocode'),
			$fieldset = $promocodeRemoveBtn.closest('fieldset'),
			$promocodeUuid = $fieldset.find('#promocode_uuid'),
			$errorMsg = $promocodeContainer.find('.text-error'),
			$successMsg = $promocodeContainer.find('.text-success');

		$errorMsg.remove();
		$successMsg.remove();
		$promocodeRemoveBtn.hide();
		$promocodeApplyBtn.show();
		$promocode.val('');
		$promocodeUuid.val('');
		$promocode.attr('disabled', false);

		calcAmount();
	});

	$(document).on('change', '#product_id', function() {
		calcAmount();

		if ($(this).find(':selected').data('product-type-alias') === 'courses') {
			$('#weekends, #has_promocode, #birthday').closest('.switch_container').addClass('hidden');
		} else {
			$('#weekends, #has_promocode, #birthday').closest('.switch_container').removeClass('hidden');
		}
	});

	$(document).on('click', '.js-card-btn', function() {
		$(this).addClass('hidden');
		$('.consent-container, .js-certificate-btn, .card-requisites').removeClass('hidden');
		$('#payment_form').card({
			container: '.card-wrapper',
			formSelectors: {
				numberInput: 'input#card_number',
				expiryInput: 'input#expiration_date',
				cvcInput: 'input#card_code',
				nameInput: 'input#card_name'
			}
		});
		/*$('html, body').animate({
			scrollTop: $('.js-certificate-btn').offset().top
		}, 500);*/
		$(this).addClass('hidden');
	});

	$(document).on('click', '.js-certificate-btn', function() {
		var $popup = $(this).closest('.popup'),
			cityId = $('#city_id').val(),
			productId = $popup.find('#product_id').val(),
			name = $popup.find('#name').val(),
			email = $popup.find('#email').val(),
			phone = $popup.find('#phone').val(),
			cardNumber = $popup.find('#card_number').val(),
			expirationDate = $popup.find('#expiration_date').val(),
			cardName = $popup.find('#card_name').val(),
			cardCode = $popup.find('#card_code').val(),
			promocode_uuid = $popup.find('#promocode_uuid').val(),
			birthday = $popup.find('#birthday').is(':checked') ? 1 : 0,
			weekends = $popup.find('#weekends').is(':checked') ? 1 : 0,
			$alertSuccess = $popup.find('.alert-success'),
			$alertError = $popup.find('.alert-danger'),
			source = $(this).data('source'),
			event_type = $(this).data('event_type'),
			url = $(this).data('url');

		$alertSuccess.html('').addClass('hidden');
		$alertError.text('').addClass('hidden');
		$('.field-error').removeClass('field-error');

		var data = {
			'source': source,
			'event_type': event_type,
			'name': name,
			'email': email,
			'phone': phone,
			'product_id': productId ? parseInt(productId) : 0,
			'city_id': cityId ? parseInt(cityId) : 0,
			'promocode_uuid': promocode_uuid,
			'card_number': cardNumber.replace(/[^\d]/g, ""),
			'expiration_date': expirationDate.replace(/[^\d]/g, ""),
			'card_name': cardName,
			'card_code': cardCode.replace(/[^\d]/g, ""),
			'birthday': birthday,
			'weekends': weekends,
		};

		$.ajax({
			url: url,
			type: 'POST',
			data: data,
			dataType: 'json',
			success: function (result) {
				//console.log(result);

				if (result.status !== 'success') {
					if (result.reason) {
						$alertError.text(result.reason).removeClass('hidden');
					}
					if (result.errors) {
						const entries = Object.entries(result.errors);
						entries.forEach(function (item, key) {
							var fieldId = item[0],
								$field = $('#' + fieldId);
							if ($field.attr('id') === 'product_id') {
								$field.next('.nice-select').addClass('field-error');
							} else {
								$field.addClass('field-error');
							}
						});
					}
					return;
				}

				$popup.find('#name, #email, #phone, #card_number, #card_name, #expiration_date, #card_code').val('');
				//$('#product_id').niceSelect('update');
				//$popup.find('.js-amount, .js-tax, .js-total-amount').text(0);
				$alertSuccess.html(result.message).removeClass('hidden');

				fbq('track', 'Purchase', {value: result.totalAmount, currency: result.currencyAlias});
			}
		});
	});
});