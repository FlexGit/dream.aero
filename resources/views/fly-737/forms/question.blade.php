<div class="questions">
	<div class="container">
		<div class="row">
			<div class="col-md-7">
				<h2>@lang('main-fly737.question.у-вас-остались-вопросы')</h2>
				<span>@lang('main-fly737.question.напишите-менеджеру-компании')</span>
				<img src="{{ asset('img/' . Session::get('domain') . '/bplane.webp') }}" alt="" width="100%" height="auto">
			</div>
			<div class="col-md-5">
				<div class="form wow fadeInRight" data-wow-duration="2s" style="text-align: center;">
					<form class="ajax_form" action="#" method="post">
						<input type="text" id="name" name="name" placeholder="YOUR NAME">
						<input type="email" id="email" name="email" placeholder="YOUR E-MAIL">
						<textarea id="body" name="body" placeholder="YOUR MESSAGE"></textarea>

						<div class="consent-container">
							<label class="cont" style="color: #fff;">
								I hereby give my consent to process my personal data.
								<input type="checkbox" name="consent" value="1">
								<span class="checkmark" style="padding-top: 0;"></span>
							</label>
						</div>

						<div>
							<div class="alert alert-success hidden" style="background-color: transparent;border-color: transparent;color: #fff;" role="alert">
								@lang('main-fly737.question.сообщение-успешно-отправлено')
							</div>
							<div class="alert alert-danger hidden" style="background-color: transparent;border-color: transparent;color: #fff;" role="alert"></div>
						</div>

						<button type="button" class="button-pipaluk button-pipaluk-white js-question-btn" disabled><i>SUBMIT</i></button>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
