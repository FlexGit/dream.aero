<?php

namespace App\Http\Middleware;

use App\Models\City;
use App\Services\HelpFunctions;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class CityCheck
{
	public function handle(Request $request, Closure $next)
	{
		if ($request->ajax()) return $next($request);
		
		$cityAliases = City::ALIASES;
		
		\Log::debug($request->segment(1));
		\Log::debug($request->session()->get('cityAlias'));
		
		if (in_array($request->segment(1), $cityAliases) && (($request->session()->get('cityAlias') && ($request->segment(1) != $request->session()->get('cityAlias'))) || !$request->session()->get('cityAlias'))) {
			$city = HelpFunctions::getEntityByAlias(City::class, $request->segment(1));
			if ($city) {
				$cityName = $city->name ?? '';
				
				$request->session()->put('cityId', $city->id);
				$request->session()->put('cityAlias', $city->alias);
				$request->session()->put('cityName', $cityName);
				
				//\Log::debug($request->session()->get('cityAlias') . ' - ' . $request->segment(1) . ' - ' . $request->segment(2));
				
				//return redirect($city->alias . ($request->segment(1) ? '/' . $request->segment(2) : ''), 301);
				return $next($request);
			}
		}

		if (in_array($request->segment(1), ['', 'contacts', 'price'])) {
			return redirect(($request->session()->get('cityAlias') ?? City::DC_ALIAS) . ($request->segment(1) ? '/' . $request->segment(1) : ''), 301);
		}
		
		return $next($request);
	}
}