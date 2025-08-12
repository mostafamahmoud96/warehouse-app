<?php
namespace App\Policies;

use App\Models\User;
use App\Util\PermissionsUtil;
use App\Exceptions\UnauthorizedActionException;

class InventoryItemPolicy
{
    /**
     * Determine whether the user can view the inventory items.
     */
    public function list(User $user): bool
    {
        $authorize = $user->hasPermissionTo(PermissionsUtil::LIST_ITEMS_WITH_WAREHOUSE, 'api');
        if (! $authorize) {
            throw new UnauthorizedActionException("You are not authorized to list inventory items.");
        }

        return true;
    }
}
