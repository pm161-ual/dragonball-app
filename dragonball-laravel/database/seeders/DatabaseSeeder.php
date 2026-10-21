<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {

        $admin = User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
        ]);

        $admin->is_admin = true;
        $admin->save();

    }
}
