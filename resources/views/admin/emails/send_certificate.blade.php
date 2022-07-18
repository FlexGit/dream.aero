<table width="100%" bgcolor="#F2F3FC" cellpadding="0" cellspacing="0" border="0">
	<tbody>
	<tr>
		<td style="padding:40px 0;">
			<table cellpadding="0" cellspacing="0" width="100%" border="0" align="center">
				<tbody>
				<tr>
					<td>
						<table cellpadding="0" cellspacing="0" border="0" width="90%" style="max-width:600px;margin:0 auto">
							<tbody>
							<tr>
								<td width="4" height="4"><p style="margin:0;font-size:1px;line-height:1px;">&nbsp;</p></td>
								<td colspan="3" rowspan="3" bgcolor="#FFFFFF" style="padding:10px 0 30px;text-align:center">
									<a href="https://dream.aero" style="display:flex;width:167px;height:auto;margin:0 auto;" target="_blank" rel=" noopener noreferrer">
										<img src="https://dream.aero/assets/img/logo-new-mail.png" width="172px" alt="logo" style="display: flex; border: 0; margin: 0;">
									</a>

									<p style="margin:15px 30px 33px;text-align:left;font-size:16px;line-height:30px;color:#484a42;">
										Dear Mr/Ms {{ $name ?? '' }}</p>
									<p style="margin:15px 30px 33px;text-align:left;font-size:16px;line-height:30px;color:#484a42;">
										Greetings from Dream Aero!</p>
									<p style="margin:15px 30px 33px;text-align:left;font-size:16px;line-height:30px;color:#484a42;">
										Thank you for choosing us.</p>
									<p style="margin:15px 30px 33px;text-align:left;font-size:14px;line-height:30px;color:#484a42;">In attachment you can find your flight voucher # <b>{{ $certificate->number }}</b>. Voucher validity <b>{{ $certificate->expire_at ? 'till ' . \Carbon\Carbon::parse($certificate->expire_at)->format('d.m.Y') : 'termless' }}</b>.</p>
									<p style="border-top:2px solid #e5e5e5;font-size:5px;line-height:5px;margin:0 30px 29px;">&nbsp;</p>
									<table cellpadding="0" cellspacing="0" border="0" width="100%">
										<tbody>
										<tr valign="top">
											<td width="30"><p style="margin:0;font-size:1px;line-height:1px;">&nbsp;</p></td>
											<td style="text-align:left;">
												<p style="margin:0 0 4px;font-weight:bold;color:#333333;font-size:14px;line-height:22px;">If you have any questions,</p>
												<p style="margin:0;color:#333333;font-size:11px;line-height:18px;">our administrator is ready to answer them.<br>Call us <a target="_blank" rel="noopener noreferrer"><span class="js-phone-number">{{ $city->phone ?? '' }}</span></a> or e-mail us <a href="mailto:{{ $city->email ?? '' }}" target="_blank" rel="noopener noreferrer">{{ $city->email ?? '' }}</a>
												</p>
												<p style="margin:10px 0;font-size:1px;line-height:1px;">&nbsp;</p>
												<a href="https://www.facebook.com/dreamaerous/" target="_blank" rel="noopener noreferrer" style="text-decoration: none;">
													<img src="https://dream.aero/assets/img/fb.png" width="24" height="24" alt="Facebook">
												</a>
												<a href="https://www.instagram.com/dreamaero.us" target="_blank" rel="noopener noreferrer" style="text-decoration: none;">
													<img src="https://dream.aero/assets/img/inst.png" width="24" height="24" alt="Instagram">
												</a>
												<a href="https://www.youtube.com/channel/UCSg-5Jw7aeZdqPOKeGC3ctA" target="_blank" rel="noopener noreferrer" style="text-decoration: none;">
													<img src="https://dream.aero/assets/img/you.png" width="24" height="24" alt="Youtube">
												</a>
												<a href="https://www.snapchat.com/add/dreamaerous" target="_blank" rel="noopener noreferrer" style="text-decoration: none;">
													<img src="https://dream.aero/assets/img/snapchat.png" width="24" height="24" alt="Snapchat">
												</a>
												<a href="https://twitter.com/dream_aero" target="_blank" rel="noopener noreferrer" style="text-decoration: none;">
													<img src="https://dream.aero/assets/img/twitter.png" width="24" height="24" alt="Twitter">
												</a>
											</td>
											<td width="30">
												<p style="margin:0;font-size:1px;line-height:1px;">&nbsp;</p>
											</td>
										</tr>
										</tbody>
									</table>
								</td>
							</tr>
							</tbody>
						</table>
					</td>
				</tr>
				</tbody>
			</table>
		</td>
	</tr>
	</tbody>
</table>
