<?php

namespace Database\Factories;

use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;

class TaskFactory extends Factory
{
    protected $model = Task::class;

    public function definition()
    {
        $date = $this->faker->dateTimeBetween('now', '+1 year')->format('d/m/Y');
        return [
            'user_id' => 1,
            'task_title' => $this->faker->sentence,
            'task_description' => $this->faker->paragraph,
            'due_date' => $date,
            'status' => $this->faker->randomElement(['pending', 'in-progress', 'completed']),
            'priority' => $this->faker->randomElement(['low', 'medium', 'high']),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
