<?php

namespace App\Http\Middleware;

use Closure;
use Carbon\Carbon;
use App\CompanyIdentity;
class PasswordExpired
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    /*public function handle($request, Closure $next)
    {
        return $next($request);
    }*/
	
	public function handle($request, Closure $next)
    {
        $user = $request->user();
		$compDetails = CompanyIdentity::first();
		$iduration = !empty($compDetails->password_expiring_month) ? $compDetails->password_expiring_month : 0;

		if (!empty($user->password_changed_at))
		{
			if (($user->password_changed_at) <= mktime(0, 0, 0, date("m")-$iduration, date("d"), date("Y")))
				return redirect('/password/expired');
		}
		else
		{
			$password_changed_at = new Carbon($user->created_at);
			if (Carbon::now()->diffInDays($password_changed_at) >= $iduration * 30)
				return redirect('/password/expired');
		}
 
        return $next($request);
    }
}
