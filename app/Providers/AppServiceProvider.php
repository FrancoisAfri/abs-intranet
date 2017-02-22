<?php

namespace App\Providers;

use App\module_access;
use App\module_ribbons;
use App\modules;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //Compose header
        view()->composer('layouts.header', function($view) {
            $user = Auth::user()->load('person');
            $defaultAvatar = ($user->person->gender === 0) ? 'avatars/f-silhouette.jpg' : 'avatars/m-silhouette.jpg';
            $avatar = $user->person->profile_pic;
            $position = ($user->person->position) ? DB::table('hr_positions')->find($user->person->position)->name : '';

            $data['user'] = $user;
            $data['full_name'] = $user->person->first_name . " " . $user->person->surname;
            $data['avatar'] = (!empty($avatar)) ? Storage::disk('local')->url("avatars/$avatar") : Storage::disk('local')->url($defaultAvatar);
            $data['position'] = $position;

            $view->with($data);
        });

        //Compose left sidebar
        view()->composer('layouts.sidebar', function($view) {
			$user = Auth::user();
            $modulesAccess = modules::whereHas('moduleRibbon', function ($query) {
                $query->where('active', 1);
            })->where('active', 1)
                ->whereIn('id', module_access::select('module_id')->where(function ($query) use ($user) {
                    $query->where('user_id', $user->id);
                    $query->whereNotNull('access_level');
                    $query->where('access_level', '>', 0);
                })->get())
                ->with(['moduleRibbon' => function ($query) use($user) {
                    $query->whereIn('id', module_ribbons::select('security_modules_ribbons.id')->join('security_modules_access', function($join) use($user) {
                        $join->on('security_modules_ribbons.module_id', '=', 'security_modules_access.module_id');
                        $join->on('security_modules_access.user_id', '=', DB::raw($user->id));
                        $join->on('security_modules_ribbons.access_level', '<=', 'security_modules_access.access_level');
                    })->get());
                    $query->orderBy('sort_order', 'ASC');
                }])
                ->orderBy('name', 'ASC')->get();

            $data['company_logo'] = Storage::disk('local')->url('logos/logo.jpg');
			$data['modulesAccess'] = $modulesAccess;
            $view->with($data);
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
