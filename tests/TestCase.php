<?php

namespace Tests;

use Database\Seeders\DatabaseSeeder;
use Database\Seeders\TestSeeder;
use Illuminate\Foundation\Testing\Concerns\InteractsWithSession;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Mcamara\LaravelLocalization\LaravelLocalization;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
    use InteractsWithSession;

    protected $seed = true;

    protected $seeder = DatabaseSeeder::class;

    protected function refreshApplicationWithLocale($locale)
    {
        self::tearDown();
        putenv(LaravelLocalization::ENV_ROUTE_KEY . '=' . $locale);
        self::setUp();
    }

    protected function tearDown(): void
    {
        putenv(LaravelLocalization::ENV_ROUTE_KEY);
        parent::tearDown();
    }
}
