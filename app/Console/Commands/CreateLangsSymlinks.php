<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CreateLangsSymlinks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lang:link';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create symbolic links for language directories';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $langs = array_keys(config('locales.supportedLanguages'));
        $countries = array_keys(config('locales.supportedCountries'));

        foreach ($langs as $lang) {
            foreach ($countries as $country) {
                $link = base_path('lang/'.$lang.'-'.$country);
                $target = base_path('lang/'.$lang);

                unlink($link);

                if (! file_exists($link)) {
                    $this->info("Attempting to create symlink from [$target] to [$link]");
                    symlink($target, $link);
                    $this->info("The [$link] link has been connected to [$target].");
                } else {
                    $this->error("The [$link] link already exists.");
                }
            }
        }

        return 0;
    }
}
