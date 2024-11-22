<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\UserNotification>
 */
class UserNotificationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $notification_types = ['article_added', 'article_updated', 'article_deleted', 'event_added', 'event_updated', 'event_deleted', 'comment_added', 'comment_deleted'];
        $data = ['header' => 'Lépieds a ajouté un événement', 'content' => 'Dégustation de fromage, le 26 juillet 2024'];

        return [
            'type' => fake()->randomElement($notification_types),
            'data' => json_encode($data),
        ];
    }
}
