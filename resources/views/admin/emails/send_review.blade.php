<p>Name: {{ $name ?? '' }}</p>
<p>Comment: {{ $body ?? '' }}</p>
<p>Sent: {{ Carbon\Carbon::now()->format('d.m.Y H:i') }}</p>
<br>
<p><small>Email sent automatically</small></p>