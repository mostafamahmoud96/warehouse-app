<?php
namespace App\Providers;

use App\Models\InventoryItem;
use App\Models\Stock;
use App\Models\StockTransfer;
use App\Models\Warehouse;
use App\Policies\InventoryItemPolicy;
use App\Policies\StockPolicy;
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
        Stock::class         => StockPolicy::class,
        InventoryItem::class => InventoryItemPolicy::class,
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
