<div class="gl-default uk-modal-dialog">
    <span class="city">Are you in — <b class="gl-city-name_ru">{{ $city->name }}</b>?</span>
	<span class="btn-yes">Yes</span>
	<span class="btn-change">Change</span>
	<ul class="gl-change-list" style="display: none;">
		@foreach ($cities as $cityItem)
			<li>
				<span class="gl-list-location js-city" data-alias="{{ $cityItem->alias }}">{{ $cityItem->name }}</span>
			</li>
		@endforeach
	</ul>
</div>
