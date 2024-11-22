<?php

namespace Database\Seeders;

use App\Models\Shop;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserFavouriteShopSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::where('role', 'user')->get();
        $shops = Shop::all();

        foreach ($users as $user) {
            $counter = rand(5, 12);
            for ($i = 0; $i < $counter; $i++) {
                $shop = $shops->random();
                $user->user_favourite_shops()->firstOrCreate([
                    'shop_id' => $shop->id,
                ]);
            }
        }
    }
}
