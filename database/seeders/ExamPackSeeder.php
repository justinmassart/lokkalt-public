<?php

namespace Database\Seeders;

use App\Models\Franchise;
use App\Models\Pack;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ExamPackSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $franchises = Franchise::whereHas('owner', function ($query) {
            $query->where('email', '!=', 'oseamatthews@example.xyz');
        })->get();

        foreach (Pack::all() as $pack) {
            foreach ($franchises as $franchise) {
                $franchise->packs()->create([
                    'is_active' => true,
                    'pack_id' => $pack->id,
                ]);
            }
        }

        $emptySellers = [];

        for ($i = 0; $i < 10; $i++) {
            $emptySellers[] = User::factory()->create([
                'email' => 'vendeur.vide.' . $i + 1 . '@mail.com',
                'password' => bcrypt('PFE-Seraing-2324-Lokkalt'),
                'role' => 'seller',
            ]);
        }

        foreach ($emptySellers as $seller) {
            $sellerFranchise = Franchise::factory()->create([
                'verified_at' => now(),
                'VAT' => str()->upper('BE' . rand(1000, 9999) . rand(100, 999) . rand(100, 999)),
                'bank_account' => '',
                'country' => 'BE',
            ]);

            $sellerFranchise->franchiseOwner()->create([
                'user_id' => $seller->id,
            ]);
        }
    }
}
