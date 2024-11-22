<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class AddTranslations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lang:translate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Retreive the translations tags in views and create the given keys in every langs folders.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $files = array_merge(
            $this->getViewsFiles(resource_path('views')),
            $this->getViewsFiles(app_path('Filament/Dashboard'))
        );

        $translations = $this->extractTranslations($files);

        $this->updateLanguageFiles($translations);

        $this->info('Done ! ' . count($files) . ' files were processed.');
    }

    private function extractTranslations(array $files): array
    {
        $translations = [];

        foreach ($files as $file) {
            $content = file_get_contents($file);
            preg_match_all('/(@lang|__)\((\'|\")(?P<expression>[^$]+?)(\'|\")\)/', $content, $matches);

            foreach ($matches['expression'] as $expression) {
                if (preg_match('/(?P<file>.*?)\.(?P<key>[^$]+?)(\..*?)?$/', $expression, $match)) {
                    $translations[$match['file']][] = $match['key'];
                }
            }
        }

        return $translations;
    }

    private function updateLanguageFiles(array $translations): void
    {
        $langFolders = array_keys(config('locales.supportedLanguages'));

        foreach ($translations as $file => $keys) {
            if ($file === 'validation') {
                continue;
            }

            $keys = array_unique($keys);

            foreach ($langFolders as $langFolder) {
                $filePath = base_path("lang/{$langFolder}/{$file}.php");

                if (file_exists($filePath)) {
                    $existingTranslations = include $filePath;
                    $newKeys = array_diff($keys, array_keys($existingTranslations));

                    if (!empty($newKeys)) {
                        $this->writeToFile($filePath, $newKeys);
                    }
                } else {
                    $this->writeToFile(base_path("lang/translations/{$file}.php"), $keys);
                }
            }
        }
    }

    private function writeToFile(string $filePath, array $keys): void
    {
        $existingContent = file_exists($filePath) ? file_get_contents($filePath) : "<?php\n\nreturn [\n];\n";
        $newContent = '';

        foreach ($keys as $key) {
            $newContent .= "\t'{$key}' => '',\n";
        }

        $newContent = str_replace("];\n", $newContent . "];\n", $existingContent);

        file_put_contents($filePath, $newContent);
    }

    private function getViewsFiles($dir, &$results = [])
    {
        $files = scandir($dir);

        foreach ($files as $key => $value) {
            $path = realpath($dir . DIRECTORY_SEPARATOR . $value);
            if (!is_dir($path)) {
                if (preg_match('/\.blade\.php$|\.php$/', $path)) {
                    $results[] = $path;
                }
            } elseif ($value != '.' && $value != '..' && $value != 'vendor') {
                $this->getViewsFiles($path, $results);
            }
        }

        return $results;
    }
}
