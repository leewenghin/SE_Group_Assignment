<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
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
        $items = [
            [
                'id' => 1,
                'email' => 'superadmin@newera.edu.my',
                'password' => '$2y$10$joOJKbdH1ABzfEO528bDA.wYJOvyMSsYo8oMDLkzwgbnyL7Fsv6EK',
                'first_name' => 'Super',
                'last_name' => 'Admin',
                'gender_id' => 1,
                'department_id' => 1,
                'avatar_file_path' => null,
                'avatar_file_name' => null,
                'email_verified_at' => now(),
                'remember_token' => Str::random(10),
            ]
        ];
        foreach ($items as $item) {
            User::updateOrCreate($item);
        }
        // User::factory()->count(50)->create(); // Fake data for testing
    }
}
