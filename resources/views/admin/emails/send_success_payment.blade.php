<p>Payment by Invoice # {{ $bill->number ?? '' }}. Invoice amount {{ $bill->amount }} USD.</p>
<p>Deal #: {{ $deal->number ?? '' }}</p>
{{--@if($position)
	<p>Позиция: {{ $position->number }}</p>
@endif--}}
@if($certificate)
	<p>Voucher #: {{ $certificate->number }}</p>
@endif
<p>Client: {{ $contractor->fio() }} (E-mail: {{ $contractor->email }}, Phone #: {{ $contractor->phone }})</p>
@if($location)
	<p>Location: {{ $location->name }}</p>
@endif
<br>
<p><small>Email sent automatically</small></p>