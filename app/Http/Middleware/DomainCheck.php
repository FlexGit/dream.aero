<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class DomainCheck
{
	public function handle(Request $request, Closure $next)
	{
		if ($request->ajax()) return $next($request);
		
		if ($request->getHost() == env('DOMAIN_SITE2')) {
			$request->session()->put('domain', 'fly-737');
		} else {
			$request->session()->put('domain', 'dream-aero');
		}
	
		return $next($request);
	}
}