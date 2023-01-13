<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Status;

class StatusSeeder extends Seeder
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
                'name' => 'Pending'
            ],
            [
                'id' => 2,
                'name' => 'KIV'
            ],
            [
                'id' => 3,
                'name' => 'Active'
            ],
            [
                'id' => 4,
                'name' => 'Done'
            ],
            [
                'id' => 5,
                'name' => 'Reprocessing'
            ],
            [
                'id' => 6,
                'name' => 'Closed'
            ],
        ];
        foreach ($items as $item) {
            Status::updateOrCreate($item);
        }
    }
}
