<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ComplaintAction;

class ComplaintActionSeeder extends Seeder
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
                'name' => 'Complaint approved by Help Desk'
            ],
            [
                'id' => 2,
                'name' => 'Complaint rejected by Help Desk'
            ],
            [
                'id' => 3,
                'name' => 'Accepted by Executive'
            ],
            [
                'id' => 4,
                'name' => 'Declined by Executive'
            ],
            [
                'id' => 5,
                'name' => 'Done by Executive'
            ],
            [
                'id' => 6,
                'name' => 'Executive task approved by Help Desk'
            ],
            [
                'id' => 7,
                'name' => 'Executive task rejected by Help Desk'
            ],
            [
                'id' => 8,
                'name' => 'Change Executive Department'
            ],
            [
                'id' => 9,
                'name' => 'Closed / Completed'
            ],
        ];
        foreach ($items as $item) {
            ComplaintAction::updateOrCreate($item);
        }
    }
}
