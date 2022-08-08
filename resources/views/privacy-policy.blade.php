@extends('layouts.master')

@section('title')
	{{ $page->meta_title }}
@stop
@section('description', $page->meta_description)

@section('content')
	<div class="breadcrumbs container"><a href="{{ url(Request::get('cityAlias') ?? '/') }}">@lang('main.home.title')</a> <span>PRIVACY AND COOKIE POLICY</span></div>

	<article class="article">
		<div class="container">
			<h1 class="article-title">PRIVACY AND COOKIE POLICY</h1>
			<div class="article-content">
				<div class="row">
					<div class="item">
						<p>The privacy of our customers is very important to us, and we are committed to safeguarding it. This policy explains what we do with your data and personal information.</p>
						<p>A privacy policy is a statement or a legal document that discloses some or all of the ways a party gathers, uses, discloses, and manages customer data and personal information.</p>
						<p>Consenting to our use of cookies remains in accordance with our terms when you first visit our website. This permits us to use cookies every time you visit our website thereafter.</p>
						<h3><strong>Our website uses cookies.</strong></h3>
						<p>A “cookie” is a file containing an identifier (a string of letters and numbers) which is sent by a web server to a web browser, and then stored by the browser. The identifier is then sent back to the server each time the browser requests a page from the server.</p>
						<p>&nbsp;Cookies can be “persistent” cookies or “session” cookies.</p>
						<p>A “persistent” cookie will be stored by a web browser and will remain valid until its expiry date, unless deleted by the user before the expiry date.</p>
						<p>A “session” cookie, on the other hand, will expire at the end of the user’s session, when the web browser is closed.</p>
						<p>We use persistent and session cookies that are provided to us by Google Analytics. Our chat system employs the use of session cookies in order to improve usability.</p>
						<p>Cookies do not typically contain any personal information pertaining to the user. They serve either to ensure the function of the site (online booking and actions within a user’s account) or for web analytics, without referencing a user’s personal information.</p>
						<p>You can block or delete the cookies that are already stored on your computer. Deleting cookies will have a negative impact on the usability of many websites.</p>
						<p><strong>What data and personal information do we collect?</strong></p>
						<ul style="list-style-type: disc;">
							<li>Username</li>
							<li>Name</li>
							<li>Date of birth</li>
							<li>Phone number(s)</li>
							<li>Email address</li>
							<li>Postal address</li>
						</ul>
						<p><strong>How and why do we use your data and personal information?</strong></p>
						<ul>
							<li>We collect and use the various information listed above, for the sole purpose of executing a public offer, namely:</li>
							<li>Emailing gift/flight voucher purchased through our website.</li>
							<li>Mailing gift/flight vouchers purchased through our website.</li>
							<li>Providing services acquired through our website.</li>
							<li>Providing you with news and/or marketing materials no more than once a month. You may unsubscribe at any time.</li>
							<li>Handling inquiries and complaints.</li>
							<li>Securing our website and preventing fraud.</li>
							<li>We will not, without your expressed consent, disclose your information to third parties.</li>
							<li>&nbsp;Disclosing your data and personal information</li>
						</ul>
						<p>We may disclose your personal information only to employees of our company, officials, suppliers and subcontractors, as reasonably necessary for the purpose of fulfilling our terms of service. We may disclose your personal information:</p>
						<p>To the extent that we are required to do so by law.</p>
						<p>In connection with any ongoing or prospective legal proceedings.</p>
						<p>In order to establish, exercise, or defend our legal rights (including providing information to others for the purposes of fraud prevention).</p>
						<p>For the delivery of a gift voucher that was purchased through our website.</p>
						<p>We will not disclose your personal information to third parties other than in the previously stated situations.</p>
						<p><strong>Retaining your data and personal information</strong></p>
						<p>We will collect, process, and use your data and personal information exclusively for the purposes of providing a public offer. Your data and personal information will not be stored any longer than what is necessary to fulfill these goals.</p>
						<p><strong>Security of your data and personal information</strong></p>
						<p>We care about the security of your data and personal information. We will take reasonable technical and organizational precautions to prevent the loss, misuse or alteration of said data, by processing and storing such only on secure servers.</p>
						<p>All electronic financial transactions concluded through our website will be protected by end to end encryption.</p>
						<p>You are responsible for ensuring that your password for our website remains confidential. We will not ask you for your password except for when you enter our website.</p>
						<p><strong>Amendments</strong></p>
						<p>We reserve the right to periodically update this policy by publishing newer versions on our website. We advise you to periodically check this page so that you are aware of any changes. We may notify you of changes to this policy via e-mail or through the private messaging system on our website if you make such a request.</p>
						<p><strong>Your rights</strong></p>
						<p>You may instruct us not to process your data and personal information at any time.</p>
						<p>You also have the right to instruct us to change your data and personal information at any time.</p>
						<p>&nbsp;</p>
						<p>All credit and debit cards, including identifiable information will not be stored, sold, shared, rented or leased to third parties under any circumstances.</p>
						<p>&nbsp;</p>
						<p>The policies as well as the terms and conditions on our website may occasionally be changed or updated to meet our standards and requirements. Therefore, users are encouraged to visit these sections in order to be up to date on any changes or amendments made to our website. Amendments will become effective the day they are published.</p>
					</div>
				</div>
			</div>
		</div>
	</article>
@endsection
