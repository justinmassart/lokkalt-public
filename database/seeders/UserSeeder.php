<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'firstname' => 'Justin',
                'lastname' => 'Massart',
                'full_name' => 'Justin Massart',
                'slug' => 'justin-massart#000001',
                'email' => 'justinmassart@outlook.com',
                'country' => 'BE',
                'phone' => '+32494391109',
                'address' => 'rue du pérréon 51, 4141 Louveigné, Liège, Belgique',
                'password' => bcrypt('PFE-Seraing-2324-Lokkalt'),
                'role' => 'admin',
            ],
            [
                'firstname' => 'Dominique',
                'lastname' => 'Vilain',
                'full_name' => 'Dominique Vilain',
                'slug' => 'dominique-vilain#000002',
                'email' => 'dominique.vilain@lokkalt.com',
                'country' => 'BE',
                'phone' => '+32400000000',
                'address' => 'rue du parlaba, 1234 Sart-Tilman, Liège, Belgique',
                'password' => bcrypt('PFE-Seraing-2324-Lokkalt'),
                'role' => 'admin',
            ],
            [
                'firstname' => 'Arthur',
                'lastname' => 'Morgan',
                'full_name' => 'Arthur Morgan',
                'slug' => 'arthur-morgan#000003',
                'email' => 'arthur.morgan@lokkalt.com',
                'country' => 'BE',
                'password' => bcrypt('PFE-Seraing-2324-Lokkalt'),
                'role' => 'moderator',
            ],
            [
                'firstname' => 'Sadie',
                'lastname' => 'Adler',
                'full_name' => 'Sadie Adler',
                'slug' => 'sadie-adler#000003',
                'email' => 'sadie.adler@lokkalt.com',
                'country' => 'BE',
                'password' => bcrypt('PFE-Seraing-2324-Lokkalt'),
                'role' => 'moderator',
            ],
            [
                'firstname' => 'Justin',
                'lastname' => 'Massart',
                'full_name' => 'Justin Massart',
                'slug' => 'justin-massart#000010',
                'email' => 'justinmssrt+local@gmail.com',
                'country' => 'BE',
                'password' => bcrypt('PFE-Seraing-2324-Lokkalt'),
                'role' => 'user',
            ],
            [
                'firstname' => 'Osea',
                'lastname' => 'Matthews',
                'full_name' => 'Osea Matthews',
                'slug' => 'osea-matthews#000020',
                'email' => 'oseamatthews@example.xyz',
                'country' => 'BE',
                'password' => bcrypt('PFE-Seraing-2324-Lokkalt'),
                'role' => 'seller',
            ],
        ];

        foreach ($users as $user) {
            User::factory()->create($user);
        }

        User::factory()->count(15)->create(['role' => 'user']);
    }
}
