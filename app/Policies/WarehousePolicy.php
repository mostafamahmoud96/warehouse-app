<?php
namespace App\Policies;

use App\Exceptions\UnauthorizedActionException;
use App\Models\User;
use App\Util\PermissionsUtil;

class WarehousePolicy
{
    /**
     * Determine if the user can view warehouses.
     */
    public function list(User $user): bool
    {
        $authorize = $user->hasPermissionTo(PermissionsUtil::LIST_WAREHOUSES_ITEMS, 'api');
        if (! $authorize) {
            throw new UnauthorizedActionException("You are not authorized to list warehouses.");
        }

        return true;
    }

}
