<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Department;

class DepartmentSeeder extends Seeder
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
                'name' => 'Registration',
                'description' => 'Registration'
            ],
            [
                'id' => 2,
                'name' => 'FICT',
                'description' => 'FICT'
            ],
            [
                'id' => 3,
                'name' => 'LIBRARY',
                'description' => 'LIBRARY'
            ],
        ];
        foreach ($items as $item) {
            Department::updateOrCreate($item);
        }
        // Department::factory()->count(10)->create();
    }
}
