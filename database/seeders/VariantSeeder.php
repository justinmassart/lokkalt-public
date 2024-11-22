<?php

namespace Database\Seeders;

use App\Models\Variant;
use App\Models\VariantPrice;
use Illuminate\Database\Seeder;

class VariantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $variants = Variant::all();

        foreach ($variants as $variant) {
            $currencies = ['EUR', 'USD', 'GBP'];

            foreach ($currencies as $currency) {
                VariantPrice::factory()->create([
                    'currency' => $currency,
                    'variant_id' => $variant->id,
                    'per' => fake()->randomElement(['unit', 'kg', 'g', 'L', 'pair', '2', '3', '4']),
                ]);
            }
        }
    }
}
