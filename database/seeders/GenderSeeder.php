<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Gender;

class GenderSeeder extends Seeder
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
                'name' => 'Male'
            ],
            [
                'id' => 2,
                'name' => 'Female'
            ]
        ];
        foreach ($items as $item) {
            Gender::updateOrCreate($item);
        }
    }
}
