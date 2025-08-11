<?php
namespace App\Policies;

use App\Models\User;

class StockTransferPolicy
{

    /**
     * Determine whether the user can create models.
     */
    public function update(User $user): bool
    {
        return $user->isAuthenticated();
    }

}
