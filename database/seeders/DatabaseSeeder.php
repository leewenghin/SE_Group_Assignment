<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        /*
        Run command:

        php artisan migrate:fresh --seed

        */
        $this->call([
            GenderSeeder::class,
            DepartmentSeeder::class, // Fake data for testing
            StatusSeeder::class,
            ComplaintActionSeeder::class,
            RoleSeeder::class,
            UserSeeder::class, // There is fake data for testing inside the class
            UserRoleSeeder::class,
            // VerifiedComplaintSeeder::class, // Fake data for testing
            // ComplaintSeeder::class, // Fake data for testing
            // ComplaintLoggingSeeder::class, // Fake data for testing
        ]);
    }
}
