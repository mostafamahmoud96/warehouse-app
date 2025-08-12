<?php
namespace App\Policies;

use App\Models\User;
use App\Util\PermissionsUtil;
use App\Exceptions\UnauthorizedActionException;

class StockPolicy
{
    /**
     * Determine whether the user can create models.
     */
    public function update(User $user): bool
    {
        $authorize = $user->hasPermissionTo(PermissionsUtil::UPDATE_STOCK, 'api');
        if (! $authorize) {
            throw new UnauthorizedActionException("You are not authorized to update the stock.");
        }

        return true;

    }

}
