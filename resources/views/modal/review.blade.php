<form id="popup-review" class="popup popup-review ajax_form">
	<p class="popup-title">
		ARE YOU IMPRESSED?
	</p>
	<p class="popup-description">
		Share your impressions with us, and we will publish it on the website!
	</p>
	<fieldset>
		<input type="text" id="name" name="name" class="popup-input" placeholder="@lang('main.modal-review.ваше-имя')" required>
		<textarea id="body" name="body" class="popup-area" placeholder="COMMENT"></textarea>

		<div class="consent-container">
			<label class="cont">
				I hereby give my consent to process my personal data.
				<input type="checkbox" name="consent" value="1">
				<span class="checkmark" style="padding-bottom: 0;"></span>
			</label>
			<a href="{{ url(Request::get('cityAlias') . '/privacy-policy') }}" target="_blank">Learn more</a>
		</div>

		<div style="margin-top: 10px;">
			<div class="alert alert-success hidden" role="alert">
				@lang('main.modal-review.отзыв-успешно-отправлен')
			</div>
			<div class="alert alert-danger hidden" role="alert"></div>
		</div>

		<button type="button" class="popup-submit button-pipaluk button-pipaluk-grey js-review-btn" disabled><i>SUBMIT</i></button>
	</fieldset>
</form>
