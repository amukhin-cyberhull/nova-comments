<?php

declare(strict_types=1);

namespace KirschbaumDevelopment\NovaComments\Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

/**
 * Class adds the permission 'comments.view_private' to the Administrator.
 */
class RoleUpdateSeeder extends Seeder
{
    /**
     * @const ADMINISTRATOR Administrator role name.
     */
    public const ADMINISTRATOR = 'Administrator';

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = [
            self::ADMINISTRATOR => [
                'comments.view_private',
            ],
        ];

        foreach ($roles as $name => $permissions) {
            $role = Role::where(['name' => $name])->first();

            foreach ($permissions as $permission) {
                $role->givePermissionTo($permission);
            }
        }
    }
}
