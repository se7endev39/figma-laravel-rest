<?php

namespace App\Http\Middleware;

use Closure;

class CanInstall
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
		if(env('APP_INSTALLED',true) == true){
       
            update_currency_exchange_rate();

			return $next($request);  
		}
		return redirect('/installation');
    }
}
