@foreach ($deals as $deal)
	@php
		$balance = $deal->balance();
	@endphp

	<tr class="odd" data-id="{{ $deal->id }}" @if($deal->status && $deal->status->alias == app('\App\Models\Deal')::CREATED_STATUS) style="background-color: #e6d8d8;" @endif>
		<td class="align-top small">
			<div class="col-12 d-inline-block text-nowrap">
				<div>
					{{ $deal->name }}
					@if($deal->contractor)
						@if($deal->contractor->email != app('\App\Models\Contractor')::ANONYM_EMAIL)
							[<a href="/contractor/{{ $deal->contractor_id }}" target="_blank">{{ $deal->contractor->fio() }}</a>]
						@else
							[{{ $deal->contractor->fio() }}]
						@endif
					@endif
				</div>
				<div>
					<i class="fas fa-mobile-alt"></i> {{ $deal->phone }}
				</div>
				<div>
					<i class="far fa-envelope"></i> {{ $deal->email }}
				</div>
			</div>
		</td>
		<td class="text-center align-top d-none d-sm-table-cell small">
			<div class="font-weight-bold" title="Deal number">
				<a href="javascript:void(0)" data-toggle="modal" data-url="/deal/{{ $deal->id }}/edit" data-action="/deal/{{ $deal->id }}" data-title="Deal editing" title="Edit deal" data-method="PUT" data-type="deal">{{ $deal->number }}</a>
			</div>
			<div class="text-nowrap" style="line-height: 0.9em;" title="Deal date">
				{{ $deal->created_at ? $deal->created_at->format('Y-m-d H:i') : '' }}
			</div>
			<div>
				<div class="d-inline-block" title="Deal amount">
					@if($deal->contractor && $deal->contractor->city)
						<i class="fas fa-dollar-sign"></i>
					@endif
					{{ number_format($deal->amount(), 2, '.', ' ') }}
				</div>
				@foreach($deal->positions as $position)
					@if($position->promocode)
						<div class="d-inline-block" title="Promocode">
							<i class="fas fa-tag"></i> {{ ($position->promocode && $position->promocode->discount) ? $position->promocode->discount->valueFormatted() : '-' }}
						</div>
					@endif
					@if($position->promo)
						<div class="d-inline-block" title="Promo">
							<i class="fas fa-percent"></i> {{ ($position->promo && $position->promo->discount) ? $position->promo->discount->valueFormatted() : '-' }}
						</div>
					@endif
				@endforeach
				<div class="d-inline-block mt-1" title="Total">
					@if($balance < 0)
						<span class="pl-2 pr-2" style="background-color: #ffbdba;">{{ number_format($balance, 2, '.', ' ') }}</span>
					@elseif($balance > 0)
						<span class="pl-2 pr-2" style="background-color: #e9ffc9;">+{{ number_format($balance, 2, '.', ' ') }}</span>
					@else
						<span class="pl-2 pr-2" style="background-color: #e9ffc9;">paid</span>
					@endif
				</div>
			</div>
			@if($deal->status)
				<div title="Deal status">
					<div class="p-0 pl-2 pr-2" style="background-color: {{ array_key_exists('color', $deal->status->data_json ?? []) ? $deal->status->data_json['color'] : 'none' }};">
						{{ $deal->status->name }}
					</div>
				</div>
			@endif
			<div class="d-flex justify-content-between mt-2">
				<div title="Source">
					{{ isset(\App\Models\Deal::SOURCES[$deal->source]) ? \App\Models\Deal::SOURCES[$deal->source] : '' }}
				</div>
				<div title="Who created">
					@if($deal->user)
						{{ $deal->user->fioFormatted() }}
					@endif
				</div>
			</div>
		</td>
		<td class="text-center align-top d-none d-xl-table-cell small">
			@foreach($deal->bills ?? [] as $bill)
				@php
					if ($bill->total_amount <= 0) continue;
					$billPosition = $bill->position;
				@endphp
				<div class="mb-3">
					<div>
						<div class="d-inline-block font-weight-bold">
							<a href="javascript:void(0)" data-toggle="modal" data-url="/bill/{{ $bill->id }}/edit" data-action="/bill/{{ $bill->id }}" data-method="PUT" data-title="Edit Invoice" data-type="bill" title="Edit Invoice">{{ $bill->number }}</a>
						</div>
						{{--<div class="d-inline-block ml-2">
							<a href="javascript:void(0)" class="js-remove-bill" data-id="{{ $bill->id }}" title="Удалить счет"><i class="fas fa-times" style="color: #aaa;"></i></a>
						</div>--}}
					</div>
					<div class="text-nowrap" style="line-height: 0.9em;" title="Create date">
						{{ $bill->created_at ? $bill->created_at->format('Y-m-d H:i') : '' }}
					</div>
					{{--@if($bill->location)
						<div class="text-nowrap" title="Location">
							{{ $bill->location->name ?? '' }}
						</div>
					@endif--}}
					<div>
						@if($bill->currency)
							<i class="fas fa-dollar-sign"></i>
						@endif
						{{ number_format($bill->total_amount, 2, '.', ' ') }}
						@if($bill->paymentMethod)
							[{{ $bill->paymentMethod->name }}]
							@if ($bill->paymentMethod->alias == app('\App\Models\PaymentMethod')::ONLINE_ALIAS && $bill->status && $bill->status->alias == app('\App\Models\Bill')::NOT_PAYED_STATUS)
								@if ($bill->link_sent_at)
									<a href="javascript:void(0)" class="js-send-pay-link ml-2" data-id="{{ $bill->id }}" title="Paylink sent {{ $bill->link_sent_at }}"><i class="far fa-envelope-open"></i></a>
								@else
									<a href="javascript:void(0)" class="js-send-pay-link ml-2" data-id="{{ $bill->id }}" title="Paylink not sent yet"><i class="far fa-envelope"></i></a>
								@endif
							@endif
						@endif
					</div>
					@if ($bill->status)
						<div class="p-0 pl-2 pr-2" style="background-color: {{ array_key_exists('color', $bill->status->data_json ?? []) ? $bill->status->data_json['color'] : 'none' }};">
							<span title="Bill status">{{ $bill->status->name }}</span>
							<span title="Payment date">{{ $bill->payed_at ? '[' . $bill->payed_at->format('Y-m-d H:i') . ']' : '' }}</span>
						</div>
					@endif
				</div>
			@endforeach
			<a href="javascript:void(0)" data-toggle="modal" data-url="/bill/{{ $deal->id }}/add" data-action="/bill" data-method="POST" data-title="Create Invoice" data-type="bill" title="Create Invoice" class="btn btn-info btn-sm">Create Invoice</a>
		</td>
		<td class="align-top text-center d-none d-md-table-cell">
			<table class="table table-sm table-bordered table-striped mb-0">
				<tr>
					<td class="col-4 small font-weight-bold">
						Type
					</td>
					<td class="col-2 small font-weight-bold">
						Product
					</td>
					<td class="col-3 small font-weight-bold">
						Flight
					</td>
					<td class="col-1"></td>
				</tr>
				@foreach($deal->positions as $position)
					<tr>
						<td class="small">
							<div>
								@if($position->is_certificate_purchase)
									<div class="font-weight-bold d-inline-block">
										{{--<a href="javascript:void(0)" data-toggle="modal" data-url="/deal_position/certificate/{{ $position->id }}/edit" data-action="/deal_position/certificate/{{ $position->id }}" data-method="PUT" data-type="position" data-title="Edit voucher purchase item {{ $position->number }}">--}}
											Voucher purchase
										{{--</a>--}}
									</div>
									{{--<div class="d-inline-block">
										<a href="javascript:void(0)" data-toggle="modal" data-url="/deal_position/certificate/{{ $position->id }}/edit" data-action="/deal_position/certificate/{{ $position->id }}" data-method="PUT" data-type="position" data-title="Edit voucher purchase item {{ $position->number }}">{{ $position->number }}</a>
									</div>--}}
									{{--<div class="d-inline-block ml-2">
										<a href="javascript:void(0)" class="js-remove-position" data-id="{{ $position->id }}" title="Delete item"><i class="fas fa-times" style="color: #aaa;"></i></a>
									</div>--}}
								@else
									@if($position->location)
										@if($position->certificate)
											<div class="font-weight-bold d-inline-block">
												{{--<a href="javascript:void(0)" data-toggle="modal" data-url="/deal_position/booking/{{ $position->id }}/edit" data-action="/deal_position/booking/{{ $position->id }}" data-method="PUT" data-type="position" data-title="Edit booking by voucher item {{ $position->number }}">--}}
													Booking by voucher
												{{--</a>--}}
											</div>
											{{--<div class="d-inline-block">
												<a href="javascript:void(0)" data-toggle="modal" data-url="/deal_position/booking/{{ $position->id }}/edit" data-action="/deal_position/booking/{{ $position->id }}" data-method="PUT" data-type="position" data-title="Edit booking by voucher item {{ $position->number }}">{{ $position->number }}</a>
											</div>--}}
											{{--<div class="d-inline-block ml-2">
												<a href="javascript:void(0)" class="js-remove-position" data-id="{{ $position->id }}" title="Delete item"><i class="fas fa-times" style="color: #aaa;"></i></a>
											</div>--}}
										@else
											<div class="font-weight-bold d-inline-block">
												{{--<a href="javascript:void(0)" data-toggle="modal" data-url="/deal_position/booking/{{ $position->id }}/edit" data-action="/deal_position/booking/{{ $position->id }}" data-method="PUT" data-type="position" data-title="Edit booking {{ $position->number }}">--}}
													Booking
												{{--</a>--}}
											</div>
											{{--<div class="d-inline-block">
												<a href="javascript:void(0)" data-toggle="modal" data-url="/deal_position/booking/{{ $position->id }}/edit" data-action="/deal_position/booking/{{ $position->id }}" data-method="PUT" data-type="position" data-title="Edit booking {{ $position->number }}">{{ $position->number }}</a>
											</div>--}}
											{{--<div class="d-inline-block ml-2">
												<a href="javascript:void(0)" class="js-remove-position" data-id="{{ $position->id }}" title="Delete item"><i class="fas fa-times" style="color: #aaa;"></i></a>
											</div>--}}
										@endif
									@else
										<div class="font-weight-bold d-inline-block">
											{{--<a href="javascript:void(0)" data-toggle="modal" data-url="/deal_position/product/{{ $position->id }}/edit" data-action="/deal_position/product/{{ $position->id }}" data-method="PUT" data-type="position" data-title="Edit good / service item {{ $position->number }}">--}}
												Good / service purchase
											{{--</a>--}}
										</div>
										{{--<div class="d-inline-block">
											<a href="javascript:void(0)" data-toggle="modal" data-url="/deal_position/product/{{ $position->id }}/edit" data-action="/deal_position/product/{{ $position->id }}" data-method="PUT" data-type="position" data-title="Edit good / service item {{ $position->number }}">{{ $position->number }}</a>
										</div>--}}
										{{--<div class="d-inline-block ml-2">
											<a href="javascript:void(0)" class="js-remove-position" data-id="{{ $position->id }}" title="Delete item"><i class="fas fa-times" style="color: #aaa;"></i></a>
										</div>--}}
									@endif
								@endif
							</div>
							@if(!$position->is_certificate_purchase)
								{{--@if($position->city)
									<div title="Location">
										<i class="fas fa-map-marker-alt"></i>
										{{ $position->city->name }}
										@if($position->location)
											<div title="Location flight">
												<i class="fas fa-map-marker-alt"></i> {{ $position->location->name }}
											</div>
										@endif
										@if($position->simulator)
											<div title="Flight simulator">
												<i class="fas fa-plane"></i> {{ $position->simulator->name }}
											</div>
										@endif
									</div>
								@endif--}}
								<div title="Desired flight date and time">
									<i class="far fa-calendar-alt"></i> {{ \Carbon\Carbon::parse($position->flight_at)->format('Y-m-d H:i') }}
								</div>
							@endif
							@if($position->certificate)
								<div class="text-nowrap">
									@if($position->certificate->product_id && $position->is_certificate_purchase && $deal->balance() >= 0)
										<a href="{{ route('getCertificate', ['uuid' => $position->certificate->uuid]) }}" class="mr-2">
											<i class="far fa-file-alt" title="Voucher file"></i>
										</a>
									@endif
									@if($position->is_certificate_purchase)
										<a href="javascript:void(0)" class="font-weight-bold" data-toggle="modal" data-url="/certificate/{{ $position->certificate->id }}/edit" data-action="/certificate/{{ $position->certificate->id }}" data-method="PUT" data-title="Edit voucher" data-type="certificate" title="Edit voucher">
									@endif
										{{ $position->certificate->number ?: 'no number' }}
									@if($position->is_certificate_purchase)
										</a>
									@endif
									@if($position->certificate->product_id && $position->is_certificate_purchase && $deal->balance() >= 0)
										@if($position->certificate->sent_at)
											<a href="javascript:void(0)" class="js-send-certificate-link ml-2" data-id="{{ $position->id }}" data-certificate_id="{{ $position->certificate->id }}" title="Voucher sent {{ $position->certificate->sent_at }}"><i class="far fa-envelope-open"></i></a>
										@else
											<a href="javascript:void(0)" class="js-send-certificate-link ml-2" data-id="{{ $position->id }}" data-certificate_id="{{ $position->certificate->id }}" title="Voucher not sent yet"><i class="far fa-envelope"></i></a>
										@endif
									@endif
								</div>
								{{--@if($position->is_certificate_purchase)
									<div title="Voucher city">
										<i class="fas fa-map-marker-alt"></i>
										{{ $position->certificate->city->name }}
									</div>
								@endif--}}
								@if ($position->certificate->status)
									<div class="p-0 pl-2 pr-2" style="background-color: {{ array_key_exists('color', $position->certificate->status->data_json ?? []) ? $position->certificate->status->data_json['color'] : 'none' }};" title="Voucher status">
										Voucher {{ $position->certificate->status->name }}
									</div>
								@endif
							@endif
							@if(is_array($position->data_json) && ((array_key_exists('comment', $position->data_json) && $position->data_json['comment']) || (array_key_exists('certificate_whom', $position->data_json) && $position->data_json['certificate_whom']) || (array_key_exists('certificate_whom_phone', $position->data_json) && $position->data_json['certificate_whom_phone']) || (array_key_exists('delivery_address', $position->data_json) && $position->data_json['delivery_address'])))
								<div class="text-left mt-2">
									<div style="border: 1px solid;border-radius: 6px;padding: 4px 8px;background-color: #fff;">
										@if(array_key_exists('comment', $position->data_json) && $position->data_json['comment'])
											<div title="Comment">
												<i class="far fa-comment-dots"></i>
												<span><i>{{ $position->data_json['comment'] }}</i></span>
											</div>
										@endif
										{{--@if(array_key_exists('certificate_whom', $position->data_json) && $position->data_json['certificate_whom'])
											<div title="Who the voucher is for (name)">
												<i class="fas fa-user"></i>
												<span><i>{{ $position->data_json['certificate_whom'] }}</i></span>
											</div>
										@endif
										@if(array_key_exists('certificate_whom_phone', $position->data_json) && $position->data_json['certificate_whom_phone'])
											<div title="Who the voucher is for (phone number)">
												<i class="fas fa-mobile-alt"></i>
												<span><i>{{ $position->data_json['certificate_whom_phone'] }}</i></span>
											</div>
										@endif
										@if(array_key_exists('delivery_address', $position->data_json) && $position->data_json['delivery_address'])
											<div title="Delivery address">
												<i class="fas fa-truck"></i>
												<span><i>{{ $position->data_json['delivery_address'] }}</i></span>
											</div>
										@endif--}}
									</div>
								</div>
							@endif
						</td>
						<td class="small">
							<div>
								<a href="javascript:void(0)" data-toggle="modal" data-url="/deal_position/certificate/{{ $position->id }}/edit" data-action="/deal_position/certificate/{{ $position->id }}" data-method="PUT" data-type="position" data-title="Edit voucher purchase item">
									<b>{{ $position->product ? $position->product->name : 'no name' }}</b>
								</a>
							</div>
							@if($position->promo)
								<div title="Promo">
									<i class="fas fa-percent"></i> {{ $position->promo->name }}
								</div>
							@endif
							@if($position->promocode)
								<div title="Promocode">
									<i class="fas fa-tag"></i> {{ $position->promocode->number }}
								</div>
							@endif
							<div class="text-right text-nowrap" title="Amount">
								<span>Amount: </span>
								@if($position->currency && $position->currency->alias == app('\App\Models\Currency')::USD_ALIAS)
									<i class="fas fa-dollar-sign"></i>
								@endif
								{{ $position->amount ? number_format($position->amount, 2, '.', ' ') : 0 }}
							</div>
							<div class="text-right text-nowrap" title="Vat">
								<span>Vat: </span>
								@if($position->currency && $position->currency->alias == app('\App\Models\Currency')::USD_ALIAS)
									<i class="fas fa-dollar-sign"></i>
								@endif
								{{ $position->tax ? number_format($position->tax, 2, '.', ' ') : 0 }}
							</div>
							<div class="text-right text-nowrap" title="Total amount">
								<span>Total amount: </span>
								@if($position->currency && $position->currency->alias == app('\App\Models\Currency')::USD_ALIAS)
									<i class="fas fa-dollar-sign"></i>
								@endif
								{{ $position->total_amount ? number_format($position->total_amount, 2, '.', ' ') : 0 }}
							</div>
						</td>
						<td class="text-center small" nowrap>
							@if(!$position->is_certificate_purchase && $position->event)
								<div class="d-inline-block" title="Flight date and time">
									<i class="far fa-calendar-alt"></i>
									{{ \Carbon\Carbon::parse($position->event->start_at)->format('Y-m-d') }}
									{{ \Carbon\Carbon::parse($position->event->start_at)->format('H:i') }} - {{ \Carbon\Carbon::parse($position->event->stop_at)->addMinutes($position->event->extra_time)->format('H:i') }}
								</div>
								<div class="d-inline-block ml-2">
									<a href="javascript:void(0)" class="js-remove-event" data-id="{{ $position->event->id }}" title="Delete event"><i class="fas fa-times" style="color: #aaa;"></i></a>
								</div>
								{{--@if($position->event->location)
									<div title="Flight location">
										<i class="fas fa-map-marker-alt"></i> {{ $position->event->location->name }}
									</div>
								@endif
								@if($position->event->simulator)
									<div title="Flight simulator">
										<i class="fas fa-plane"></i> {{ $position->event->simulator->name }}
									</div>
								@endif--}}
								@if($position->event->event_type == app('\App\Models\Event')::EVENT_TYPE_TEST_FLIGHT)
									<div><span class="font-weight-bold">Test flight</span></div>
								@endif
								<div>
									{{--@if ($position->event->uuid)
										<a href="{{ route('getFlightInvitation', ['uuid' => $position->event->uuid ]) }}">
											<i class="far fa-file-alt" title="Download flight invitation"></i>
										</a>
									@endif--}}
									@if($position->event->flight_invitation_sent_at)
										<a href="javascript:void(0)" class="js-send-flight-invitation-link ml-2" data-id="{{ $position->id }}" data-event_id="{{ $position->event->id }}" title="Flight invitation sent {{ $position->event->flight_invitation_sent_at }}"><i class="far fa-envelope-open"></i></a>
									@else
										<a href="javascript:void(0)" class="js-send-flight-invitation-link ml-2" data-id="{{ $position->id }}" data-event_id="{{ $position->event->id }}" title="Flight invitation not sent yet"><i class="far fa-envelope"></i></a>
									@endif
								</div>
								@if(count($position->event->comments))
									<div class="text-center mt-2" style="margin: 0 auto;max-width: 300px;" title="Comment">
										<div class="text-left" style="line-height: 0.8em;border: 1px solid;border-radius: 10px;padding: 4px 8px;background-color: #fff;white-space: normal;">
											@foreach($position->event->comments ?? [] as $comment)
												<div>
													<i class="far fa-comment-dots"></i> <i>{{ $comment->name }}</i>
												</div>
												@if ($comment->updatedUser)
													<div class="text-right text-nowrap mb-2">
														<small>Edited: {{ $comment->updatedUser->name }} {{ \Carbon\Carbon::parse($comment->updated_at)->format('Y-m-d H:i') }}</small>
													</div>
												@elseif ($comment->createdUser)
													<div class="text-right text-nowrap mb-2">
														<small>Created: {{ $comment->createdUser->name }} {{ \Carbon\Carbon::parse($comment->created_at)->format('Y-m-d H:i') }}</small>
													</div>
												@endif
											@endforeach
										</div>
									</div>
								@endif
							@endif
						</td>
						<td class="text-center align-middle">
							@if(!$position->is_certificate_purchase && $position->location)
								@if($position->event && $position->event->event_type != app('\App\Models\Event')::EVENT_TYPE_TEST_FLIGHT)
									<div>
										<a href="javascript:void(0)" data-toggle="modal" data-url="/event/{{ $position->event->id }}/edit" data-action="/event/{{ $position->event->id }}" data-method="PUT" data-title="Edit event" data-type="event" title="Edit event" class="btn btn-success btn-sm"><i class="far fa-calendar-alt"></i></a>
									</div>
								@else
									<div>
										<a href="javascript:void(0)" data-toggle="modal" data-url="/event/{{ $position->id }}/add" data-action="/event" data-method="POST" data-title="Create event" data-type="event" title="Create event" class="btn btn-warning btn-sm"><i class="far fa-calendar-plus"></i></a>
									</div>
								@endif
							@endif
						</td>
					</tr>
				@endforeach
			</table>
			<div class="text-right small mt-1 mb-1" style="line-height: 0.9em;">
				<div class="btn-group dropleft">
					<a href="javascript:void(0)" class="btn btn-secondary btn-sm dropdown-toggle" role="button" id="dropdownMenuLink-{{ $deal->id }}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="Add item">Add item</a>

					<div class="dropdown-menu" aria-labelledby="dropdownMenuLink-{{ $deal->id }}" style="z-index: 9999;">
						<a href="javascript:void(0)" data-toggle="modal" data-url="/deal_position/certificate/add/{{ $deal->id }}" data-action="/deal_position/certificate" data-method="POST" data-type="position" data-title="Add voucher purchase item" class="btn btn-secondary btn-sm dropdown-item">Voucher purchase</a>
						<a href="javascript:void(0)" data-toggle="modal" data-url="/deal_position/booking/add/{{ $deal->id }}" data-action="/deal_position/booking" data-method="POST" data-type="position" data-title="Add booking item" class="btn btn-secondary btn-sm dropdown-item">Booking</a>
						<a href="javascript:void(0)" data-toggle="modal" data-url="/deal_position/product/add/{{ $deal->id }}" data-action="/deal_position/product" data-method="POST" data-type="position" data-title="Add good / service item" class="btn btn-secondary btn-sm dropdown-item">Good / service</a>
					</div>
				</div>
			</div>
		</td>
	</tr>
@endforeach