<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

class DocumentsFactory extends Factory
{
    public function definition(): array
    {
        $userId = User::inRandomOrder()->first()?->id ?? User::factory();
        return [
            'user_id' => $userId,
            'doc_type' => $this->faker->randomElement([
                'Photo_Passport_Size',
                'UID_Card',
                'Driver_license',
                'HighSchool',
                'Intermediate',
                'Pen_Card'
            ]),
            'doc_desc' => $this->faker->sentence(),
            'doc_url' => $this->faker->url(),
            'deleted' => false,
            'deleted_by' => null,
            'deleted_at' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
