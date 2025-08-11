<?php
namespace App\Policies;

use App\Models\User;

class StockTransferPolicy
{

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->isAuthenticated() && $user->hasPermissionTo('create-transfer');
}

}
