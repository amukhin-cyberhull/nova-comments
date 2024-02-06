<?php

declare(strict_types=1);

namespace KirschbaumDevelopment\NovaComments\Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

/**
 * Seeder of 'comments.view_private' permission.
 */
class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = [
            'comments.view_private',
        ];

        foreach ($permissions as $perm) {
            $permission = Permission::create(['name' => $perm]);
        }
    }
}
