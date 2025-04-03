<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ReportUser>
 */
class ReportUserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'reporter_id'      => \App\Models\User::factory(),
            'reported_user_id' => \App\Models\User::factory(),
            'type_report_id'   => \App\Models\TypeReportUser::factory(),
            'description'      => $this->faker->paragraph,
            'status'           => 'pending',
        ];
    }
}
