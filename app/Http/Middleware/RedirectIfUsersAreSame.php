<?php

namespace App\Http\Middleware;

use Closure;

class RedirectIfUsersAreSame
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($request->input('receiverId') == $request->input('senderId')) {
            return redirect()->back()->with('danger', 'Нельзя осуществить перевод самому себе');
        }
        return $next($request);
    }
}
