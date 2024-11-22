<?php

namespace App\Console\Commands;

use App\Models\Category;
use App\Models\Shop;
use Illuminate\Console\Command;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use Spatie\Sitemap\SitemapGenerator;
use Spatie\Sitemap\Tags\Url;

class GenerateSitemap extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sitemap:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate the sitemap.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $sitemap = SitemapGenerator::create('https://lokkalt.test/')->getSitemap();

        $locales = array_keys(config('locales.supportedLanguages'));
        $countries = array_keys(config('locales.supportedCountries'));

        $sitemap->add(Url::create('/')->setPriority(0.5));

        // MODELS

        $categories = Category::all();

        foreach ($countries as $country) {

            foreach ($locales as $locale) {

                // SET APP LOCALE

                app()->setLocale($locale . '-' . $country);

                // HOME URL

                $sitemap->add(
                    Url::create('/' . $locale . '-' . $country)->setPriority(1)
                );

                // APP URLS

                $sitemap->add(
                    Url::create(
                        LaravelLocalization::getURLFromRouteNameTranslated(app()->currentLocale(), 'routes.categories')
                    )->setPriority(0.5)
                );

                foreach ($categories as $category) {

                    $sitemap->add(
                        Url::create(
                            LaravelLocalization::getURLFromRouteNameTranslated(app()->currentLocale(), 'routes.category', ['category' => str()->slug($category->slug)])
                        )->setPriority(0.5)
                    );

                    foreach ($category->sub_categories as $subCategory) {

                        $sitemap->add(
                            Url::create(
                                LaravelLocalization::getURLFromRouteNameTranslated(app()->currentLocale(), 'routes.category', [
                                    'category' => str()->slug($category->slug),
                                    'subCategory' => str()->slug($subCategory->slug),
                                ])
                            )->setPriority(0.5)
                        );
                    }
                }

                $sitemap->add(
                    Url::create(
                        LaravelLocalization::getURLFromRouteNameTranslated(app()->currentLocale(), 'routes.articles')
                    )->setPriority(0.5)
                );

                $sitemap->add(
                    Url::create(
                        LaravelLocalization::getURLFromRouteNameTranslated(app()->currentLocale(), 'routes.shops')
                    )->setPriority(0.5)
                );

                $sitemap->add(
                    Url::create(
                        LaravelLocalization::getURLFromRouteNameTranslated(app()->currentLocale(), 'routes.login')
                    )->setPriority(0.5)
                );

                $sitemap->add(
                    Url::create(
                        LaravelLocalization::getURLFromRouteNameTranslated(app()->currentLocale(), 'routes.create-my-account')
                    )->setPriority(0.5)
                );

                // SHOPS URL

                $shops = Shop::whereCountry($country)->get();

                foreach ($shops as $shop) {

                    $sitemap->add(
                        Url::create(
                            LaravelLocalization::getURLFromRouteNameTranslated(app()->currentLocale(), 'routes.shop', ['shop' => $shop->slug])
                        )->setPriority(0.5)
                    );

                    // ARTICLES URL

                    foreach ($shop->articles as $article) {

                        foreach ($article->variants as $variant) {

                            $sitemap->add(
                                Url::create(
                                    LaravelLocalization::getURLFromRouteNameTranslated(app()->currentLocale(), 'routes.article', ['shop' => $shop->slug, 'article' => $article->slug, 'variant' => $variant->slug])
                                )->setPriority(0.5)
                            );
                        }
                    }
                }
            }
        }

        $sitemap->writeToFile(public_path('sitemap.xml'));

        $filePath = public_path('sitemap.xml');

        if (file_exists($filePath)) {
            try {
                Storage::disk('s3')
                    ->putFileAs(
                        'web/sitemap',
                        new File($filePath),
                        'sitemap.xml'
                    );
                $this->info('The sitemap has been uploaded to S3 at web/sitemap/sitemap.xml');
            } catch (\Throwable $th) {
                $this->error('The sitemap coudl’t be uploaded.');
            }
        } else {
            $this->error('Sitemap file not found.');
        }
    }
}
