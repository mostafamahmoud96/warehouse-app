<?php
namespace App\Providers;

use App\Models\StockTransfer;
use App\Models\Warehouse;
use App\Policies\StockTransferPolicy;
use App\Policies\WarehousePolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{

    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        Warehouse::class     => WarehousePolicy::class,
        StockTransfer::class => StockTransferPolicy::class,
    ];

    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

    }
}
