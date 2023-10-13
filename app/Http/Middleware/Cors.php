<?php

namespace App\Http\Middleware;
use Closure;
use http\Env\Response;

class Cors
{
    public function handle($request, Closure $next)
    {
//        header('Access-Control-Allow-Origin: *');
//
//        // ALLOW OPTIONS METHOD
//        $headers = [
//            'Access-Control-Allow-Methods' => 'POST, GET, OPTIONS, PUT, DELETE',
//            'Access-Control-Allow-Headers' => 'Content-Type, X-Auth-Token, Origin, Authorization'
//        ];
//        if ($request->getMethod() == "OPTIONS") {
//            return Response::  response('', 200)
//                ->header('Access-Control-Allow-Origin', '*')
//                ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS')
//                ->header('Access-Control-Allow-Headers', 'Content-Type, Accept, Authorization, X-Requested-With, Application','ip');
//        }
//
//        $response = $next($request);
//        foreach ($headers as $key => $value)
//            $response->header($key, $value);
//        return $response;
////         Handle preflight requests
//        if ($request->isMethod('OPTIONS')) {

//        }
//
//        // Handle actual requests
//        return $next($request)
//            ->header('Access-Control-Allow-Origin', '*')
//            ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS')
//            ->header('Access-Control-Allow-Headers', 'Content-Type, Accept, Authorization, X-Requested-With, Application','ip');
    }
}


