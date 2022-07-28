<p>Name: {{ $name ?? '' }}</p>
<p>Comment: {{ $body ?? '' }}</p>
<p>Sent: {{ Carbon\Carbon::now()->format('m-d-Y g:i A') }}</p>
<br>
<p><small>Email sent automatically</small></p>