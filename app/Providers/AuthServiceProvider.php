<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

use App\Models\SuratMasuk;
use App\Models\SuratKeluar;
use App\Models\Disposisi;

use App\Policies\SuratMasukPolicy;
use App\Policies\SuratKeluarPolicy;
use App\Policies\DisposisiPolicy;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        SuratMasuk::class  => SuratMasukPolicy::class,
        SuratKeluar::class => SuratKeluarPolicy::class,
        Disposisi::class   => DisposisiPolicy::class,
    ];

    public function boot(): void {}
}
