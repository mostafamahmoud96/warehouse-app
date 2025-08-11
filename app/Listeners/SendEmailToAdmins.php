<?php
namespace App\Listeners;

use App\Events\LowStockDetected;

class SendEmailToAdmins
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(LowStockDetected $event): void
    {
        $adminids = [1, 2, 3]; // Example admin IDs, replace with actual logic to fetch admin users
        // Mail::to('admin@example.com')->send(new LowStockAlertMail($event->alertedQuantities));

    }
}
