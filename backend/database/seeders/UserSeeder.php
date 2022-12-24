<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            [
                'name' => 'admin',
                'email' => 'admin@admin.com',
                'password' => Hash::make('password'),
                'role' => User::ROLES['ADMIN'],    
            ],
            [
                'name' => 'manager',
                'email' => 'manager@manager.com',
                'password' => Hash::make('password'),
                'role' => User::ROLES['MANAGER'],
            ],
            [
                'name' => 'hoge',
                'email' => 'hoge@hoge.com',
                'password' => Hash::make('password'),
                'role' => User::ROLES['USER'],
            ]
        ]);

    }
}
