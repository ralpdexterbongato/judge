<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
class adminOnly
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
      if (Auth::user()->role==0)
      {
        return $next($request);
      }else
      {
        return redirect('/rating-create')->with('error', 'Only administrators are allowed');
      }
    }
}
