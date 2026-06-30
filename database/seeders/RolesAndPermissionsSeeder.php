<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $permissions = [
            'role-list',
            'role-create',
            'role-edit',
            'role-delete',
            'user-list',
            'user-create',
            'user-edit',
            'user-delete',
            'report-list',
            'report-create',
            'report-edit',
            'report-delete',
            'report-submit',
        ];

        foreach ($permissions as $permission) {
            Permission::findOrCreate($permission, 'web');
        }

        $adminRole = Role::findOrCreate('admin', 'web');
        $managerRole = Role::findOrCreate('manager', 'web');
        $userRole = Role::findOrCreate('user', 'web');

        $adminRole->syncPermissions($permissions);
        $managerRole->syncPermissions([
            'user-list',
            'user-create',
            'user-edit',
            'report-list',
            'report-create',
            'report-edit',
            'report-submit',
        ]);
        $userRole->syncPermissions([
            'report-list',
            'report-create',
            'report-edit',
            'report-submit',
        ]);

        $firstUser = User::query()->oldest('id')->first();

        if (! $firstUser) {
            $firstUser = User::query()->updateOrCreate([
                'name' => 'Super Admin',
                'email' => 'admin@foc1.com.pk',
            ], [
                'name' => 'Super Admin',
                'email' => 'admin@foc1.com.pk',
                'password' => Hash::make('password'),
            ]);
        }

        if (! $firstUser->hasRole('admin')) {
            $firstUser->assignRole('admin');
        }
    }
}
