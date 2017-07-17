<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

use App\Models\Backend\UserMenu;

class BackendAuth
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
        if ($request->segment(1) == backend_url() && $guard == backend_guard() && ! Auth::guard(backend_guard())->check()) {
            session(['tmp_login_redirect' => url()->current()]);
            return redirect(route(backend_guard(). '.auth.login'));
        }

        $user = Auth::guard(backend_guard())->user();

        $user_menu = new UserMenu;

        if ($request->segment(2) != '' && ( ! in_array($request->segment(2), config('constant.backend.allowed_url')))) {

            $curent_menu = $user_menu->getUserMenuInfoByPath($request->segment(2));

            if ( ! $user_menu->checkUserHaveRightsMenu($user['user_group_id'], $curent_menu['id']) || ($user->is_superadmin != 1 && $current_menu['is_superadmin'] == 1) ) {
                if ($request->ajax()) {
                    return response()->json(['redirect_auth' => backend_path('.index')]);
                }
                return response()->view(backend_path('.errors.401'), [], 401);
            }
        }

        return $next($request);
    }
}
