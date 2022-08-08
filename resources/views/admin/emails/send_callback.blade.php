<p>Name: {{ $name ?? '' }}</p>
<p>Phone number: {{ $phone ?? '' }}</p>
<p>Comment: {{ $comment ?? '' }}</p>
<p>Request sent: {{ Carbon\Carbon::now()->format('m-d-Y g:i A') }}</p>
<br>
<p><small>Email sent automatically</small></p>