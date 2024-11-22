<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ExamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::factory()->create([
            'firstname' => 'Godefroy',
            'lastname' => 'de Montmirail',
            'full_name' => 'Godefroy de Montmirail',
            'slug' => 'godefroy-de-montmirail#123456',
            'email' => 'godefroy@mail.com',
            'country' => 'BE',
            'password' => bcrypt('PFE-Seraing-2324-Lokkalt'),
            'role' => 'seller',
        ]);
    }
}
