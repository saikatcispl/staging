<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;
use Auth,
    Redirect,
    Session,
    Lang;

class AuthMiddleware {

    /**
     * The Guard implementation.
     *
     * @var Guard
     */
    protected $auth;

    /**
     * Create a new filter instance.
     *
     * @param  Guard  $auth
     * @return void
     */
    public function __construct(Guard $auth) {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next) {

        $bypassArr = [
            'login',
            'logout',
            'register',
            'forgot-password',
        ];

        if (!Auth::check()) {
            $urls = explode('/', $request->url());
            if (!in_array($urls[(count($urls)) - 1], $bypassArr) &&
                    $request->path() != "/" &&
                    !preg_match('@^reset-password@', $request->path()) && !preg_match('@^account-verify@', $request->path())) {
                if ($request->ajax()) {
                    return response(Lang::get('messages.AUTH.UNAUTHENTICATE'), 401);
                } else {
                    return redirect('login');
                }
            }
        }
        return $next($request);
    }

}
