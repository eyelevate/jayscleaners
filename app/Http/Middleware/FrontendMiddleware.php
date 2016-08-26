<?php

namespace App\Http\Middleware;
use Redirect;
use App\Job;
use Closure;
use Illuminate\Support\Facades\Auth;
use Session;
use URL;
use Laracasts\Flash\Flash;

class FrontendMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (!Auth::check()) {

            // Check where the user came from, if from admins then redirect accordingly
            // Set intended page
            Session::put('intended_url',$request->url());

            Flash::error('You must be logged in to view the page');
            return Redirect::action('PagesController@getLogin');
        }
        if (Auth::guard($guard)->guest()) {
            if ($request->ajax()) {
                return response('Unauthorized.', 401);
            } else {
                return redirect()->guest('/');
            }
        }

        return $next($request);
    }
}
