<p>Client: {{ $contractorFio ?? '' }}</p>
<p>Contact person: {{ $dealName ?? '' }}</p>
<p>Phone #: {{ $dealPhone ?? '' }}</p>
<p>E-mail: {{ $dealEmail ?? '' }}</p>
<p>Deal #: {{ $dealNumber ?? '' }}</p>
{{--<p>Тип сделки: {{ $isCertificatePurchase ? 'покупка сертификата' : 'бронирование' }}</p>--}}
{{--<p>Статус сделки: {{ $statusName ?? '' }}</p>--}}
<p>Voucher #: {{ $certificateNumber ?? '' }}</p>
<p>Voucher validity: @if($certificateExpireAt) {{ Carbon\Carbon::parse($certificateExpireAt)->format('m/d/Y') }} @else termless @endif</p>
<p>City: {{ $cityName }}</p>
<p>Product: {{ $productName ?? '' }}
<p>Flight duration: {{ $duration ?? '' }} min</p>
<p>Deal amount: {{ number_format($amount ?? 0, 0, '.', ' ') }} {{ $currency ?? '' }}</p>
@if($promoName)
	<p>Promo: {{ $promoName }}</p>
@endif
@if($promocodeNumber)
	<p>Promocode: {{ $promocodeNumber }}</p>
@endif
{{--@if($comment)
	<p>Дополнительная информация: {{ $comment }}</p>
@endif--}}
<p>Deal source: {{ $source ?? '' }}</p>
<p>Deal date: {{ $updatedAt ? Carbon\Carbon::parse($updatedAt)->format('m/d/Y g:i A') : '' }}</p>
<br>
<p><small>Email sent automatically</small></p>