<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UserNotification;
use Illuminate\Database\Seeder;

class UserNotificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();

        foreach ($users as $user) {
            $counter = rand(5, 13);
            for ($i = 0; $i < $counter; $i++) {
                UserNotification::factory()->create([
                    'user_id' => $user->id,
                ]);
            }
        }
    }
}
