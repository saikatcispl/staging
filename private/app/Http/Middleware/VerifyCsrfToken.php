<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as BaseVerifier;
use Auth,
    App\Helpers\Helpers,
    Redirect,
    Session,
    Lang;

class VerifyCsrfToken extends BaseVerifier {

    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        'reset-password',
    ];

    public function handle($request, Closure $next) {
        try {
            return parent::handle($request, $next);
        } catch (\Illuminate\Session\TokenMismatchException $e) {
            if (Auth::check()) {
                setCsrfCookie(Session::get('_token'));
                return $next($request);
            }

            return response(Lang::get('messages.AUTH.TOKEN_MISMATCH'), 401);
        }
    }

    protected function tokensMatch($request) {
        $token = $request->ajax() ? ($request->header('X-CSRF-Token') ? $request->header('X-CSRF-Token') : $request->header('X-XSRF-Token')) : $request->input('_token');
        return $request->session()->token() == $token;
    }

    /**
     * This will return a bool value based on route checking.
     * @param  Request $request
     * @return boolean
     */
    protected function excludedRoutes($request) {        
        foreach ($this->routes as $route) {
            if ($request->is($route)) {
                return true;
            }
        }

        return false;
    }

}
