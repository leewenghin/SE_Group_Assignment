<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Complaint;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Complaint>
 */
class ComplaintFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    protected $model = Complaint::class;

    public function definition()
    {
        return [
            'title' => fake()->slug(),
            'description' => fake()->paragraph(),
            'user_id' => rand(2, 51),
            'status_id' => rand(1, 6),
            'verified_complaint_id' => ((rand(0, 2) != 0) ? rand(1, 10) : null),
        ];
    }
}
