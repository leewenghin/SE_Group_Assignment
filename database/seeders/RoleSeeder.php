<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
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
                'name' => 'Administrator'
            ],
            [
                'id' => 2,
                'name' => 'Help Desk'
            ],
            [
                'id' => 3,
                'name' => 'Executive'
            ],
        ];
        foreach ($items as $item) {
            Role::updateOrCreate($item);
        }
    }
}
