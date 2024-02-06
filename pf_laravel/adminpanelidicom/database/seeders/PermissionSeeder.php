<?php


namespace Database\Seeders;


use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class PermissionSeeder extends Seeder
{

    protected array $permissionMap = [
        'super_admin' => [],
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (Arr::flatten($this->permissionMap) as $permission) {
            Permission::findOrCreate($permission);
        }

        foreach ($this->permissionMap as $role => $permissions) {

            /** @var Role $role */
            $role = Role::findOrCreate($role);
            $role->givePermissionTo($permissions);
        }

        app()[PermissionRegistrar::class]->forgetCachedPermissions();
    }
}
