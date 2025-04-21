<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
{
    if (Auth::check()) {
        $user = Auth::user();
        if ($request->is('profile')) {
            return $next($request);
        }
        if ($request->is('profile/*')) {
            $segments = $request->segments(); 
            $profileId = $segments[1] ?? null; 
    
            if ($profileId != $user->id) {
                return redirect('/profile');
            }
    
            return $next($request);
        }
        if ($user->role_id === 1) {
            return redirect('/dashboard/admin');
        } elseif ($user->role_id === 2) {
            return redirect('/dashboard/mahasiswa');
        } elseif ($user->role_id === 3) {
            return redirect('/dashboard/ketua-jurusan');
        } elseif ($user->role_id === 4) {
            return redirect('/dashboard/dosen-wali');
        }
          elseif ($user->role_id === 5) {
            return redirect('/dashboard/bagian-keuangan');
        }
    }
    if ($request->is('profile') || $request->is('profile/*')) {
        return redirect('/');
    }
    return $next($request);
    }
}
