<?php
namespace App\Listeners;

use App\Events\LowStockDetected;
use App\Mail\QuantityAlertMail;
use App\Services\AdminService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class SendEmailToAdmins implements ShouldQueue
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
        $adminids = app(AdminService::class)->getAdminIds();
        Mail::to($adminIds)->send(new QuantityAlertMail($event->alertedQuantities));
    }
}
