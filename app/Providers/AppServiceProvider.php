<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Ensure Doctrine DBAL is loaded
        if (class_exists(Type::class)) {
            $platform = Schema::getConnection()->getDoctrineSchemaManager()->getDatabasePlatform();

            // Check if sysname type exists before adding it
            if (!$platform->hasDoctrineTypeMappingFor('sysname')) {
                $platform->registerDoctrineTypeMapping('sysname', 'string');
            }
        }
    }
}
