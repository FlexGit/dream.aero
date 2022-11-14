@foreach ($deals as $deal)
	@php
		$balance = $deal->balance();
	@endphp

	<tr class="odd" data-id="{{ $deal->id }}" @if($deal->status && $deal->status->alias == app('\App\Models\Deal')::CREATED_STATUS) style="background-color: #e6d8d8;" @endif>
		<td class="text-center align-top small">
			<table class="table table-sm table-bordered table-striped mb-0">
				<thead>
				<tr>
					<th class="col-2">Client</th>
					<th class="col-2">
						@if($deal->is_certificate_purchase)
							Voucher purchase
						@else
							@if($deal->location)
								@if($deal->certificate)
									Booking by Voucher
								@else
									Booking
								@endif
							@else
								@if($deal->product && $deal->product->productType && $deal->product->productType->alias == app('\App\Models\ProductType')::TAX_ALIAS)
									Tax
								@else
									Good / Service purchase
								@endif
							@endif
						@endif
					</th>
					<th class="col-1">Product</th>
					<th class="col-2">Amount</th>
					<th class="col-2">
						@if($deal->is_certificate_purchase)
							Voucher
						@elseif($deal->location_id)
							Event
						@endif
					</th>
					<th class="col-2">Invoice</th>
					{{--<th class="col-1"></th>--}}
				</tr>
				</thead>
				<tbody>
					<tr>
						<td class="text-left">
							<div class="d-inline-block text-nowrap">
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
						</td>
						<td>
							<div>
								<a href="javascript:void(0)" data-toggle="modal" data-url="/deal/{{ $deal->id }}/edit" data-action="/deal/{{ $deal->id }}" data-title="Edit @if($deal->is_certificate_purchase) Voucher purchase @elseif($deal->location_id) Booking @else Good / Service purchase @endif" data-method="PUT" data-type="deal" class="{{--btn btn-secondary btn-sm--}}font-weight-bold">
									{{ $deal->number }}
								</a>
							</div>
							<div style="line-height: 0.9em;" title="Create date">
								{{ $deal->created_at->format('m/d/Y g:i A') }}
							</div>
							@if($deal->status)
								<div title="Deal status">
									<div class="p-0 pl-2 pr-2" style="background-color: {{ array_key_exists('color', $deal->status->data_json ?? []) ? $deal->status->data_json['color'] : 'none' }};">
										{{ $deal->status->name }}
									</div>
								</div>
							@endif
							@if(!$deal->is_certificate_purchase && $deal->certificate)
								<div title="Booking by Voucher">
									{{ $deal->certificate->number ?: '-' }}
								</div>
							@endif
							<div class="d-flex justify-content-between">
								<div title="Source">
									{{ isset(\App\Models\Deal::SOURCES[$deal->source]) ? \App\Models\Deal::SOURCES[$deal->source] : '' }}
								</div>
								@if($deal->user)
									<div title="User">
										{{ $deal->user->fioFormatted() }}
									</div>
								@endif
							</div>
							@if(is_array($deal->data_json) && ((array_key_exists('comment', $deal->data_json) && $deal->data_json['comment'])))
								<div class="text-left mt-2">
									<div style="border: 1px solid;border-radius: 6px;padding: 4px 8px;background-color: #fff;">
										<div title="Comment">
											<i class="far fa-comment-dots"></i>
											<span><i>{{ $deal->data_json['comment'] }}</i></span>
										</div>
									</div>
								</div>
							@endif
						</td>
						<td>
							<div title="Product name">
								{{ $deal->product ? $deal->product->name : '-' }}
							</div>
							@if($deal->promocode)
								<div title="Promocode">
									<i class="fas fa-tag"></i> {{ $deal->promocode->number }} {{ $deal->promocode->discount ? $deal->promocode->discount->valueFormatted() : '-' }}
								</div>
							@endif
							@if($deal->promo)
								<div title="Promo">
									<i class="fas fa-percent"></i> {{ $deal->promo->name }} {{ $deal->promo->discount ? $deal->promo->discount->valueFormatted() : '-' }}
								</div>
							@endif
						</td>
						<td class="text-right">
							<div class="text-nowrap" title="Subtotal">
								<span>Subtotal: </span>
								@if($deal->currency && $deal->currency->alias == app('\App\Models\Currency')::USD_ALIAS)
									<i class="fas fa-dollar-sign"></i>
								@endif
								{{ $deal->amount ? number_format($deal->amount, 2, '.', ' ') : 0 }}
							</div>
							<div class="text-nowrap" title="Tax">
								<span>Tax: </span>
								@if($deal->currency && $deal->currency->alias == app('\App\Models\Currency')::USD_ALIAS)
									<i class="fas fa-dollar-sign"></i>
								@endif
								{{ $deal->tax ? number_format($deal->tax, 2, '.', ' ') : 0 }}
							</div>
							<div class="text-nowrap" title="Total">
								<span>Total: </span>
								@if($deal->currency && $deal->currency->alias == app('\App\Models\Currency')::USD_ALIAS)
									<i class="fas fa-dollar-sign"></i>
								@endif
								{{ $deal->total_amount ? number_format($deal->total_amount, 2, '.', ' ') : 0 }}
							</div>
							<div class="text-nowrap" title="Balance">
								<span>Balance: </span>
								@if($balance < 0)
									<span class="pl-2 pr-2" style="background-color: #ffbdba;">
										@if($deal->currency && $deal->currency->alias == app('\App\Models\Currency')::USD_ALIAS)
											<i class="fas fa-dollar-sign"></i>
										@endif
										{{ number_format($balance, 2, '.', ' ') }}
									</span>
								@elseif($balance > 0)
									<span class="pl-2 pr-2" style="background-color: #e9ffc9;">
										@if($deal->currency && $deal->currency->alias == app('\App\Models\Currency')::USD_ALIAS)
											<i class="fas fa-dollar-sign"></i>
										@endif
										+{{ number_format($balance, 2, '.', ' ') }}
									</span>
								@else
									<span class="pl-2 pr-2" style="background-color: #e9ffc9;">
										@if($deal->currency && $deal->currency->alias == app('\App\Models\Currency')::USD_ALIAS)
											<i class="fas fa-dollar-sign"></i>
										@endif
										0
									</span>
								@endif
							</div>
						</td>
						<td>
							@if($deal->is_certificate_purchase && $deal->certificate)
								<div>
									<div class="d-inline-block text-nowrap">
										@if($deal->certificate->product_id && $deal->is_certificate_purchase && $deal->balance() >= 0)
											<a href="{{ route('getCertificate', ['uuid' => $deal->certificate->uuid]) }}" class="mr-2">
												<i class="far fa-file-alt" title="Voucher File"></i>
											</a>
										@endif
										@if($deal->is_certificate_purchase)
											<a href="javascript:void(0)" class="font-weight-bold" data-toggle="modal" data-url="/certificate/{{ $deal->certificate->id }}/edit" data-action="/certificate/{{ $deal->certificate->id }}" data-method="PUT" data-title="Edit Voucher {{ $deal->certificate->number ?: '-' }}" data-type="certificate" title="Edit Voucher">
										@endif
										{{ $deal->certificate->number ?: '-' }}
										@if($deal->is_certificate_purchase)
											</a>
										@endif
										@if($deal->certificate->product_id && $deal->is_certificate_purchase && $deal->balance() >= 0)
											@if($deal->certificate->sent_at)
												<a href="javascript:void(0)" class="js-send-certificate-link ml-2" data-id="{{ $deal->id }}" data-certificate_id="{{ $deal->certificate->id }}" title="{{ $deal->certificate->sent_at }}"><i class="far fa-envelope-open"></i></a>
											@else
												<a href="javascript:void(0)" class="js-send-certificate-link ml-2" data-id="{{ $deal->id }}" data-certificate_id="{{ $deal->certificate->id }}" title="Voucher not sent yet"><i class="far fa-envelope"></i></a>
											@endif
										@endif
									</div>
									@if($deal->certificate->status)
										<div class="p-0 pl-2 pr-2" style="background-color: {{ array_key_exists('color', $deal->certificate->status->data_json ?? []) ? $deal->certificate->status->data_json['color'] : 'none' }};" title="Voucher status">
											{{ $deal->certificate->status->name }}
										</div>
									@endif
								</div>
							@elseif($deal->location && $deal->event)
								{{--<table class="table table-sm table-bordered table-striped mb-0">
									<tr>
										<td class="col-3 text-center small" nowrap>--}}
											{{--@if($deal->event)--}}
												<div class="d-inline-block">
													<span>
														@if($deal->event->event_type == app('\App\Models\Event')::EVENT_TYPE_DEAL)
															<span class="font-weight-bold">Client Flight</span>
															{!! $deal->event->is_repeated_flight ? ' <small class="text-danger">[RF]</small>' : '' !!}
															{!! $deal->event->is_unexpected_flight  ? ' <small class="text-danger">[SF]</small>' : '' !!}
														@elseif($deal->event->event_type == app('\App\Models\Event')::EVENT_TYPE_TEST_FLIGHT)
															<span class="font-weight-bold">Test Flight</span>
														@elseif($deal->event->event_type == app('\App\Models\Event')::EVENT_TYPE_USER_FLIGHT)
															<span class="font-weight-bold">User Flight</span>
														@endif
													</span>
												</div>
												<div class="d-inline-block ml-2">
													<a href="javascript:void(0)" class="js-remove-event" data-id="{{ $deal->event->id }}" title="Delete event"><i class="fas fa-times" style="color: #aaa;"></i></a>
												</div>
												<div class="text-nowrap">
													<div class="d-inline-block" title="Flight date and time">
														<i class="far fa-calendar-alt"></i>
														{{ \Carbon\Carbon::parse($deal->event->start_at)->format('m/d/Y') }}
														{{ \Carbon\Carbon::parse($deal->event->start_at)->format('g:i A') }} - {{ \Carbon\Carbon::parse($deal->event->stop_at)->format('g:i A') }}
														@if($deal->event->extra_time)
															<small class="text-danger">+{{ $deal->event->extra_time }} min</small>
														@endif
													</div>
													<div class="d-inline-block">
														{{--@if($deal->event->uuid)
															<a href="{{ route('getFlightInvitation', ['uuid' => $deal->event->uuid ]) }}">
																<i class="far fa-file-alt" title="Download flight invitation"></i>
															</a>
														@endif--}}
														@if($deal->event->flight_invitation_sent_at)
															<a href="javascript:void(0)" class="js-send-flight-invitation-link ml-2" data-id="{{ $deal->id }}" data-event_id="{{ $deal->event->id }}" title="Flight invitation sent {{ $deal->event->flight_invitation_sent_at }}"><i class="far fa-envelope-open"></i></a>
														@else
															<a href="javascript:void(0)" class="js-send-flight-invitation-link ml-2" data-id="{{ $deal->id }}" data-event_id="{{ $deal->event->id }}" title="Flight invitation not sent yet"><i class="far fa-envelope"></i></a>
														@endif
													</div>
												</div>
												@if(count($deal->event->comments))
													<div class="text-center mt-2" style="margin: 0 auto;max-width: 300px;" title="Comment">
														<div class="text-left" style="line-height: 0.8em;border: 1px solid;border-radius: 10px;padding: 4px 8px;background-color: #fff;white-space: normal;">
															@foreach($deal->event->comments ?? [] as $comment)
																<div>
																	<i class="far fa-comment-dots"></i> <i>{{ $comment->name }}</i>
																</div>
																@if ($comment->updatedUser)
																	<div class="text-right text-nowrap mb-2">
																		<small>Edited: {{ $comment->updatedUser->name }} {{ $comment->updated_at->format('m/d/Y g:i A') }}</small>
																	</div>
																@elseif ($comment->createdUser)
																	<div class="text-right text-nowrap mb-2">
																		<small>Created: {{ $comment->createdUser->name }} {{ $comment->created_at->format('m/d/Y g:i A') }}</small>
																	</div>
																@endif
															@endforeach
														</div>
													</div>
												@endif
											{{--@endif--}}
										{{--</td>
										<td class="col-1 text-center align-middle">
											@if($deal->event && $deal->event->event_type != app('\App\Models\Event')::EVENT_TYPE_TEST_FLIGHT)
												<div>
													<a href="javascript:void(0)" data-toggle="modal" data-url="/event/{{ $deal->event->id }}/edit" data-action="/event/{{ $deal->event->id }}" data-method="PUT" data-title="Edit event" data-type="event" title="Edit event" class="btn btn-success btn-sm"><i class="far fa-calendar-alt"></i></a>
												</div>
											@else
												<div>
													<a href="javascript:void(0)" data-toggle="modal" data-url="/event/{{ $deal->id }}/add" data-action="/event" data-method="POST" data-title="Create event" data-type="event" title="Create event" class="btn btn-warning btn-sm"><i class="far fa-calendar-plus"></i></a>
												</div>
											@endif
										</td>
									</tr>
								</table>--}}
							@endif
						</td>
						<td>
							@foreach($deal->bills ?? [] as $bill)
								@if(!$loop->first)
									<hr>
								@endif
								<div class="mb-2">
									<div>
										<div class="d-inline-block font-weight-bold">
											<a href="javascript:void(0)" data-toggle="modal" data-url="/bill/{{ $bill->id }}/edit" data-action="/bill/{{ $bill->id }}" data-method="PUT" data-title="Edit Invoice {{ $bill->number }}" data-type="bill" title="Edit Invoice">{{ $bill->number }}</a>
										</div>
									</div>
									<div>
										@if($bill->currency && $bill->currency->alias == app('\App\Models\Currency')::USD_ALIAS)
											<i class="fas fa-dollar-sign"></i>
										@endif
										{{ number_format($bill->total_amount, 2, '.', ' ') }}
										@if($bill->paymentMethod)
											[{{ $bill->paymentMethod->name }}]
											@if ($bill->paymentMethod->alias == app('\App\Models\PaymentMethod')::ONLINE_ALIAS && $bill->status && $bill->status->alias == app('\App\Models\Bill')::NOT_PAYED_STATUS)
												@if ($bill->link_sent_at)
													<a href="javascript:void(0)" class="js-send-pay-link ml-2" data-id="{{ $bill->id }}" title="{{ $bill->link_sent_at }}"><i class="far fa-envelope-open"></i></a>
												@else
													<a href="javascript:void(0)" class="js-send-pay-link ml-2" data-id="{{ $bill->id }}" title="Paylink not sent yet"><i class="far fa-envelope"></i></a>
												@endif
											@endif
										@endif
									</div>
									@if ($bill->status)
										<div class="p-0 pl-2 pr-2" style="background-color: {{ array_key_exists('color', $bill->status->data_json ?? []) ? $bill->status->data_json['color'] : 'none' }};">
											<span title="Bill status">{{ $bill->status->name }}</span>
											<span title="Payment date">{{ $bill->payed_at ? '[' . $bill->payed_at->format('m/d/Y g:i A'). ']' : '' }}</span>
										</div>
										<div class="text-nowrap">
											@if($bill->status->alias == app('\App\Models\Bill')::PAYED_STATUS)
												<a href="{{ route('getReceipt', ['uuid' => $bill->uuid]) }}" class="ml-2" data-id="{{ $bill->id }}" title="Download Invoice Receipt"><i class="far fa-file-alt"></i></a>
												<a href="javascript:void(0)" class="js-print-receipt-link ml-2" data-id="{{ $bill->id }}" title="Print Invoice Receipt" onclick="var w = window.open('{{ route('getReceipt', ['uuid' => $bill->uuid, 'print' => true]) }}', 'mywin'); w.print();"><i class="fas fa-print"></i></a>
												@if ($bill->receipt_sent_at)
													<a href="javascript:void(0)" class="js-send-receipt-link ml-2" data-id="{{ $bill->id }}" title="{{ $bill->receipt_sent_at }}"><i class="far fa-envelope-open"></i></a>
												@else
													<a href="javascript:void(0)" class="js-send-receipt-link ml-2" data-id="{{ $bill->id }}" title="Invoice Receipt not sent yet"><i class="far fa-envelope"></i></a>
												@endif
											@endif
										</div>
									@endif
								</div>
							@endforeach
							@if($deal->passiveBalance() < 0)
								<a href="javascript:void(0)" data-toggle="modal" data-url="/bill/{{ $deal->id }}/add" data-action="/bill" data-method="POST" data-title="Create Invoice" data-type="bill" title="Create Invoice" class="btn btn-info btn-sm">Create Invoice</a>
							@endif
						</td>
						{{--<td>
							<div>
								<a href="javascript:void(0)" data-toggle="modal" data-url="/deal/{{ $deal->id }}/edit" data-action="/deal/{{ $deal->id }}" data-title="Edit @if($deal->is_certificate_purchase) Voucher purchase @elseif($deal->location_id) Booking @else Good / Service purchase @endif" data-method="PUT" data-type="deal" class="btn btn-secondary btn-sm">Edit</a>
							</div>
						</td>--}}
					</tr>
				</tbody>
			</table>
		</td>
	</tr>
@endforeach