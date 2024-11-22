<?php

use App\Filament\Dashboard\Resources\ArticleResource\Pages\ListArticles;
use App\Models\User;
use Database\Seeders\TestSeeder;

use function Pest\Laravel\actingAs;
use function Pest\Livewire\livewire;

/* beforeEach(function () {
    $this->seed(TestSeeder::class);
});

it('ensures that only users with a role of seller can log into the dashboard', function () {
    $user = User::all()->where('role', 'user')->random();
    actingAs($user);

    livewire(ListArticles::class)->assertStatus(403);
});
 */
