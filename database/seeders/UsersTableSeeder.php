<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = User::where('roles', 'ADMIN')->first();
        if (!$admin) {
            User::create([
                'name' => 'Admin',
                'email' => 'admin@gmail.com',
                'roles' => 'ADMIN',
                'username' => 'admin',
                'password' => Hash::make(12345678)
            ]);
        }

        User::factory()->times(10)->create();
    }
}
