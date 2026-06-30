<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Manager — no developer_type (admin-side staff)
        $manager = User::updateOrCreate(['email' => 'manager@foc.com'], [
            'name' => 'Project Manager',
            'company_name' => 'OneNetwork',
            'developer_type' => null,
            'password' => Hash::make('password'),
        ]);
        $manager->syncRoles(['manager']);

        // Zone Developer (developer_type = 1)
        $developer = User::updateOrCreate(['email' => 'developer@sez.com'], [
            'name' => 'Zone Developer',
            'company_name' => 'SEZ Development Authority',
            'developer_type' => 1,
            'password' => Hash::make('password'),
        ]);
        $developer->syncRoles(['user']);

        // Enterprise Developer 1 (developer_type = 2)
        $enterprise1 = User::updateOrCreate(['email' => 'enterprise1@sez.com'], [
            'name' => 'Alpha Manufacturing Ltd',
            'company_name' => 'Alpha Manufacturing Ltd',
            'developer_type' => 2,
            'password' => Hash::make('password'),
        ]);
        $enterprise1->syncRoles(['user']);

        // Enterprise Developer 2 (developer_type = 2)
        $enterprise2 = User::updateOrCreate(['email' => 'enterprise2@sez.com'], [
            'name' => 'Beta Textiles Co.',
            'company_name' => 'Beta Textiles Co.',
            'developer_type' => 2,
            'password' => Hash::make('password'),
        ]);
        $enterprise2->syncRoles(['user']);

        // Legacy user from old seeder — mark as enterprise
        $legacyUser = User::updateOrCreate(['email' => 'user@enterprise.com'], [
            'name' => 'Enterprise User',
            'company_name' => 'Echo Oil Pvt Ltd',
            'developer_type' => 2,
            'password' => Hash::make('password'),
        ]);
        $legacyUser->syncRoles(['user']);
    }
}
