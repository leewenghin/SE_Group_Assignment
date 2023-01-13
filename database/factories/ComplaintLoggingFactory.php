<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\ComplaintLogging;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ComplaintLogging>
 */
class ComplaintLoggingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

     protected $model = ComplaintLogging::class;

    public function definition()
    {
        return [
            'verified_complaint_id' => rand(1, 10),
            'user_id' => rand(2, 51),
            'assigned_to_department_id' => rand(1, 10),
            'remark' => fake()->paragraph(),
            'status_id' => rand(1, 6),
            'complaint_action_id' => rand(1, 9),
        ];
    }
}
