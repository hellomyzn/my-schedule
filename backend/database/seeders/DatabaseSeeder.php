<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        User::factory()->create([
            'name' => 'admin',
            'email' => 'admin@admin.com',
            'role' => User::ROLES['ADMIN'],
        ]);

        User::factory()->create([
            'name' => 'manager',
            'email' => 'manager@manager.com',
            'role' => User::ROLES['MANAGER'],
        ]);

        User::factory()->create([
            'name' => 'hoge',
            'email' => 'hoge@hoge.com',
            'role' => User::ROLES['USER'],
        ]);

    }
}
