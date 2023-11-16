<?php

namespace App\Http\Middleware;


use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckVerified
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if(!Auth::check()){
            $response['error'] = 'user not logged in system , error❗';
            $response['code'] = 0;
            return response()->json($response,401);
        }
        elseif (auth()->user()->verified == false) {
            $response['error'] = 'The phone number must be verified';
            $response['code'] = 3;
            auth()->logout();
            return response()->json($response,411);
        }else{
            return $next($request);
        }
    }
}
