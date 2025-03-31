<?php

namespace App\Http\Middleware;

use Auth;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
  /**
   * Handle an incoming request.
   *
   * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
   */
  public function handle(Request $request, Closure $next, ...$roles)
  {
    if (!Auth::check()) {
      return redirect('/login')->with('error', 'Vui lòng đăng nhập');
    }

    // Check if user has the required role
    if (!in_array(Auth::user()->role, $roles)) {
      return redirect('/')->with('error', 'Không có quyền truy cập.');
    }

    return $next($request);
  }

}
