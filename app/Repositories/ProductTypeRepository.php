<?php

namespace App\Repositories;

use App\Models\ProductType;
use App\Models\User;

class ProductTypeRepository {
	
	private $model;
	
	public function __construct(ProductType $productType) {
		$this->model = $productType;
	}
	
	/**
	 * @param User $user
	 * @param bool $onlyActive
	 * @param bool $onlyTariff
	 * @param bool $onlyNotTariff
	 * @param bool $onlyTax
	 * @return array
	 */
	public function getActualProductList(User $user, $onlyActive = true, $onlyTariff = true, $onlyNotTariff = false, $onlyTax = false)
	{
		$productTypes = $this->model->orderBy('name')
			->get();
		if ($onlyTariff) {
			$productTypes = $productTypes->where('is_tariff', true);
		}
		if ($onlyNotTariff) {
			$productTypes = $productTypes->where('is_tariff', false);
		}
		if ($onlyActive) {
			$productTypes = $productTypes->where('is_active', true);
		}
		if ($onlyTax) {
			$productTypes = $productTypes->where('alias', 'tax');
		}
		
		$products = [];
		foreach ($productTypes as $productType) {
			foreach ($productType->products as $product) {
				if ($onlyActive && !$product->is_active) continue;

				if (!$onlyTax) {
					if ($onlyActive) {
						$cityProduct = $product->cities()
							->where('cities_products.is_active', true)
							->find($user->city->id);
					} else {
						$cityProduct = $product->cities()
							->find($user->city->id);
					}
					if (!$cityProduct || !$cityProduct->pivot) continue;
				}
				
				$products[$productType->name][$product->id] = $product;
			}
		}
		
		return $products;
	}
}