<form id="popup-callback" class="popup popup-call-back ajax_form">
	<h2>@lang('main-fly737.modal-callback.заказать-обратный-звонок')</h2>
	<span>Fill out just a couple of fields and we will contact you in the nearest future</span>

	<input type="text" id="name" name="name" placeholder="Your name" class="popup-input" required>
	<input type="tel" id="phone" name="phone" placeholder="What is your phone number" class="popup-input" required>

	<div class="consent-container">
		<label class="cont">
			I hereby give my consent to process my personal data.
			<input type="checkbox" name="consent" value="1">
			<span class="checkmark" style="padding-bottom: 0;"></span>
		</label>
	</div>

	<div style="margin-top: 10px;">
		<div class="alert alert-success hidden" role="alert">
			@lang('main-fly737.modal-callback.запрос-успешно-отправлен')
		</div>
		<div class="alert alert-danger hidden" role="alert"></div>
	</div>

	<button type="button" class="popup-submit button-pipaluk button-pipaluk-grey js-callback-btn" disabled><i>@lang('main-fly737.common.отправить')</i></button>
</form>
