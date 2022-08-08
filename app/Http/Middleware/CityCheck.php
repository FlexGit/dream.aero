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
		
		if (in_array($request->segment(1), $cityAliases) && (($request->session()->get('cityAlias') && ($request->segment(1) != $request->session()->get('cityAlias'))) || !$request->session()->get('cityAlias'))) {
			$city = HelpFunctions::getEntityByAlias(City::class, $request->segment(1));
			if ($city) {
				$request->session()->put('cityId', $city->id);
				$request->session()->put('cityAlias', $city->alias);
				$request->session()->put('cityName', $city->name);
				$request->session()->put('cityPhone', $city->phone);
				
				//return redirect($city->alias . ($request->segment(1) ? '/' . $request->segment(2) : ''), 301);
				return $next($request);
			}
		}
		
		if (!in_array($request->segment(1), $cityAliases)) {
			return redirect(($request->session()->get('cityAlias') ?? City::UAE_ALIAS) . ($request->segment(1) ? '/' . $request->segment(1) : ''), 301);
		}
		
		return $next($request);
	}
}