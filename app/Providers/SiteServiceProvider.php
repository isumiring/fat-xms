<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;

use App\Models\Backend\Site;
use App\Models\Backend\UserMenu;

class SiteServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(Site $site, UserMenu $user_menu)
    {
        $site_info = $site->getSiteInfo();

        View::share('site_info', $site_info);

        if (request()->segment(1) == backend_url() && request()->ajax() == false) {

            if (request()->segment(2) != '' && ! in_array(request()->segment(2), config('constant.backend.allowed_url'))) {

                $menu_info = $user_menu->getUserMenuInfoByPath(request()->segment(2));

                if (count($menu_info)) {
                    $active_menus[] = $menu_info;

                    $active_menus = $user_menu->getActiveMenuWithParent($menu_info['parent_id'], $active_menus);

                    $collect_menu = collect($active_menus);

                    $grouped_menu = $collect_menu->groupBy('id');

                    View::share('active_menus', $grouped_menu);

                    view()->composer(backend_path('.layouts.partials.breadcrumbs'), function ($view) use ($user_menu, $active_menus, $menu_info) {
                        $breadcrumbs = collect($user_menu->getBreadcrumbs($active_menus, $menu_info['id']));
                        
                        View::share('breadcrumbs', $breadcrumbs->reverse()->all());
                    });
                    
                    View::share('menu_info', $menu_info);
                }
            }

            view()->composer(backend_path('.layouts.sections.sidebar'), function ($view) use ($user_menu) {
                if (Auth::guard(backend_guard())->check()) {
                    $user_group_id = Auth::guard(backend_guard())->user()->user_group_id;
                    $auth_menu = $user_menu->getAuthMenuByGroup($user_group_id)->threaded('parent_id');

                    View::share('auth_menu', $auth_menu);
                }
            });  

        }
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('fat', function() {
            return new \App\Support\FatLib;
        });
    }
}
