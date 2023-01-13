<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\VerifiedComplaint;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\VerifiedComplaint>
 */
class VerifiedComplaintFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    protected $model = VerifiedComplaint::class;

    public function definition()
    {
        return [
            'assigned_to_department_id' => rand(1, 10),
            'common_title' => fake()->slug(),
            'description' => fake()->paragraph(),
            'status_id' => rand(1, 6),
            'complaint_action_id' => rand(1, 9),
            'finalize_remark' => fake()->paragraph(),
        ];
    }
}
