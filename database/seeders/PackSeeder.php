<?php

namespace Database\Seeders;

use App\Models\Franchise;
use App\Models\Pack;
use App\Models\User;
use Illuminate\Database\Seeder;

class PackSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $packs = [
            'base' => [
                'features' => [
                    'visible_shop',
                    'articles_management',
                    'online_orders',
                    'take_away_management',
                    'stocks_management',
                    'stocks_alerts',
                ],
                'prices' => [
                    [
                        'country' => 'BE',
                        'price' => 20.00,
                        'currency' => 'EUR',
                        'stripe_id' => 'price_1PRb4jCoxzjWcobxJKyiQq9U',
                    ],
                    [
                        'country' => 'LU',
                        'price' => 25.00,
                        'currency' => 'EUR',
                        'stripe_id' => 'price_1PRb5dCoxzjWcobxXE0wZuxz',
                    ],
                ],
            ],
            'monitoring' => [
                'features' => [
                    'shop_monitoring',
                ],
                'prices' => [
                    [
                        'country' => 'BE',
                        'price' => 5.00,
                        'currency' => 'EUR',
                        'stripe_id' => 'price_1PRb56CoxzjWcobxRbJpiZyL',
                    ],
                    [
                        'country' => 'LU',
                        'price' => 5.00,
                        'currency' => 'EUR',
                        'stripe_id' => 'price_1PRb5zCoxzjWcobx4vli3bsD',
                    ],
                ],
            ],
        ];

        foreach ($packs as $packName => $data) {
            $pack = Pack::create([
                'name' => $packName,
                'is_active' => $packName === 'base' || $packName === 'monitoring' ? true : false,
            ]);

            foreach ($data['features'] as $feature) {
                $pack->features()->create([
                    'name' => $feature,
                ]);
            }

            foreach ($data['prices'] as $price) {
                $price = $pack->prices()->create([
                    'price' => $price['price'],
                    'stripe_id' => $price['stripe_id'],
                    'currency' => $price['currency'],
                    'country' => $price['country'],
                ]);
            }
        }

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
