<?php
namespace App\Policies;

use App\Exceptions\UnauthorizedActionException;
use App\Models\User;
use App\Util\PermissionsUtil;

class StockTransferPolicy
{

    /**
     * Determine whether the user can create models.
     */
    public function update(User $user): bool
    {
        $authorize = $user->hasPermissionTo(PermissionsUtil::UPDATE_STOCK_TRANSFER, 'api');
        if (! $authorize) {
            throw new UnauthorizedActionException("You are not authorized to update stock transfers.");
        }

        return true;

    }

}
