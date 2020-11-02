<?php

namespace App\Http\Middleware;
use App\Job;
use Closure;
use Redirect;
use Illuminate\Support\Facades\Auth;
use Session;
use URL;
use Laracasts\Flash\Flash;

class Authenticate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (!Auth::check()) {

            // Check where the user came from, if from admins then redirect accordingly
            $redirect_path = ($request->is('admins') || $request->is('admins/*')) ? '/admins/login' : '/';
            // Set intended page
            Session::put('intended_url',$request->url());

            Flash::error('You must be logged in to view the page');
            return redirect($redirect_path);
        }
        if (Auth::guard($guard)->guest()) {
            if ($request->ajax()) {
                return response('Unauthorized.', 401);
            } else {
                return redirect()->guest('login');
            }
        }
        if (Auth::user()->role_id == 5) {
            Flash::error('You do not have authorization to view this page');
            return Redirect::route('pages_index');
        }

        return $next($request);
    }
}
