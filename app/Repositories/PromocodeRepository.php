<?php

namespace App\Repositories;

use App\Models\City;
use App\Models\Promocode;

class PromocodeRepository {
	
	private $model;
	
	public function __construct(Promocode $promocode) {
		$this->model = $promocode;
	}
	
	/**
	 * @param City $city
	 * @param bool $onlyActive
	 * @param bool $onlyNoPersonal
	 * @return \Illuminate\Support\Collection
	 */
	public function getList(City $city, $onlyActive = true, $onlyNoPersonal = true, $contractorId = 0)
	{
		$promocodes = $this->model->whereRelation('cities', 'cities.id', '=', $city->id)
			->orderBy('number');
		if ($onlyActive) {
			$date = date('Y-m-d H:i:s');
			$promocodes = $promocodes->where('is_active', true)
				->where(function ($query) use ($date) {
					$query->where('active_from_at', '<=', $date)
						->orWhereNull('active_from_at');
				})
				->where(function ($query) use ($date) {
					$query->where('active_to_at', '>=', $date)
						->orWhereNull('active_to_at');
				});
		}
		if ($onlyNoPersonal) {
			$promocodes = $promocodes->where('contractor_id', 0);
		}
		if ($contractorId) {
			$promocodes = $promocodes->whereIn('contractor_id', [$contractorId, 0]);
		}
		$promocodes = $promocodes->get();
		
		return $promocodes;
	}
}