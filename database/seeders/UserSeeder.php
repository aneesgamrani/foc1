<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Manager
        $manager = User::updateOrCreate([
            'email' => 'manager@foc.com',
        ], [
            'name' => 'Project Manager',
            'company_name' => 'OneNetwork',
            'password' => Hash::make('password'),
        ]);
        $manager->assignRole('manager');

        // Enterprise User
        $user = User::updateOrCreate([
            'email' => 'user@enterprise.com',
        ], [
            'name' => 'Enterprise User',
            'company_name' => 'Echo Oil Pvt Ltd',
            'password' => Hash::make('password'),
        ]);
        $user->assignRole('user');
    }
}
