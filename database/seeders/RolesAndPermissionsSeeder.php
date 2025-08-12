<?php
namespace Database\Seeders;

use App\Models\User;
use App\Util\PermissionsUtil;
use App\Util\RolesUtil;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = RolesUtil::getLocalConstants();

        foreach ($roles as $role) {
            Role::firstOrCreate([
                'name'       => $role,
                'guard_name' => 'api',
            ]);
        }

        $permissions = PermissionsUtil::getLocalConstants();
        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name'       => $permission,
                'guard_name' => 'api',
            ]);

        }

        $adminRole   = Role::findByName(RolesUtil::ADMIN, 'api');
        $managerRole = Role::findByName(RolesUtil::MANAGER, 'api');
        $userRole    = Role::findByName(RolesUtil::USER, 'api');

        $adminRole->syncPermissions($permissions);
        $managerRole->syncPermissions($permissions);
        $userRole->syncPermissions([
            PermissionsUtil::LIST_WAREHOUSES_ITEMS,
        ]);

        $admin = User::where('email', 'admin@example.com')->first();
        if ($admin) {
            $admin->assignRole('admin');
        }

        $manager = User::where('email', 'manager@example.com')->first();
        if ($manager) {
            $manager->assignRole('manager');
        }

        $user = User::where('email', 'user@example.com')->first();
        if ($user) {
            $user->assignRole('user');
        }
    }

}
