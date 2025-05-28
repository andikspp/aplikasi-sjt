<?php

namespace App\Providers;

use App\Models\Allowance;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // untuk notifikasi badge persetujuan di navbar
        View::composer('layout.admin.admin-layout', function ($view) {
            $adminId = auth('admin')->id();
            $pendingPersetujuanCount = 0;
            if ($adminId) {
                $pendingPersetujuanCount = Allowance::where('requested_by', '!=', $adminId)
                    ->where('status', 'pending')
                    ->whereDoesntHave('approvals', function ($q) use ($adminId) {
                        $q->where('admin_id', $adminId);
                    })
                    ->count();
            }
            $view->with('pendingPersetujuanCount', $pendingPersetujuanCount);
        });
    }
}
