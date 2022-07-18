<p>Name: {{ $name ?? '' }}</p>
<p>Phone #: {{ $phone ?? '' }}</p>
<p>Request sent: {{ Carbon\Carbon::now()->format('d.m.Y H:i') }}</p>
<br>
<p><small>Email sent automatically</small></p>