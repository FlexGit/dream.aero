<div class="gl-default uk-modal-dialog">
    <span class="city">Ваш город — <b class="gl-city-name_ru">{{ $city->name }}</b></span>
	<span class="btn-yes">Да</span>
	<span class="btn-change">Изменить</span>
	<ul class="gl-change-list" style="display: none;">
		@foreach ($cities as $cityItem)
			<li>
				<span class="gl-list-location js-city" data-alias="{{ $cityItem->alias }}">{{ $cityItem->name }}</span>
			</li>
		@endforeach
	</ul>
</div>
