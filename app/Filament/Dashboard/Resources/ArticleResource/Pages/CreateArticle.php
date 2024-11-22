<?php

namespace App\Filament\Dashboard\Resources\ArticleResource\Pages;

use App\Filament\Dashboard\Resources\ArticleResource;
use App\Models\Article;
use App\Models\ArticleImage;
use App\Models\Image;
use App\Models\ShopArticle;
use App\Models\VariantImage;
use Aws\Rekognition\RekognitionClient;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Http\File;
use Illuminate\Support\Facades\File as FacadesFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Drivers\Gd\Driver as GdDriver;
use Intervention\Image\ImageManager;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class CreateArticle extends CreateRecord
{
    protected static string $resource = ArticleResource::class;

    protected ?bool $hasDatabaseTransactions = true;

    public array $shopIDs = [];

    public function getTitle(): string
    {
        return ucfirst(__('filament.add_article'));
    }

    protected function validateImages(array $data): array
    {
        $rekognition = new RekognitionClient([
            'region' => config('services.ses.region'),
            'version' => 'latest',
            'credentials' => [
                'key' => config('services.ses.key'),
                'secret' => config('services.ses.secret'),
            ],
        ]);

        $forbiddenLabels = [
            'Explicit',
            'Swimwear or Underwear',
            'Non-Explicit Nudity of Intimate parts and Kissing',
            'Violence',
            'Visually Disturbing',
            'Rude Gestures',
            'Drugs & Tobacco',
            'Gambling',
            'Hate Symbols',
        ];

        if (is_string($data['url'])) {
            $data['url'] = [$data['url']];
        }

        foreach ($data['url'] as $index => $url) {
            $imageToAnalyze = file_get_contents(storage_path('app/livewire-tmp/'.$url->getFileName()));

            $rekognitionData = [
                'Bytes' => $imageToAnalyze,
            ];
            $maxLabels = 10;
            $minConfidence = 75;

            $result = $rekognition->detectModerationLabels([
                'Image' => $rekognitionData,
                'MaxLabels' => $maxLabels,
                'MinConfidence' => $minConfidence,
            ]);

            $forbiddenLabelDetected = false;

            foreach ($result['ModerationLabels'] as $label) {
                if (in_array($label['Name'], $forbiddenLabels)) {
                    $forbiddenLabelDetected = true;
                    $returnedRules[] = $label['Name'];
                    break;
                }
            }

            if ($forbiddenLabelDetected) {
                unlink(storage_path('app/livewire-tmp/'.$url->getFileName()));
                unset($data['url'][$index]);

                continue;
            }
        }

        return $data;
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        unset($data['shops']);

        $data['slug'] = str()->slug($data['name']);
        $data['shopSlug'] = auth()->user()->shops()->first()->slug;

        $images = $this->validateImages($data['images']);

        $data['images'] = $images;

        $this->saveArticleTemporaryImages($data);

        // TODO: use Image Model saveTempImages fn

        unset($data['shopSlug']);
        unset($data['images']);

        $data['reference'] = str()->random(12);

        while (Article::whereReference($data['reference'])->exists()) {
            $data['reference'] = str()->random(12);
        }

        return $data;
    }

    protected function saveArticleTemporaryImages(array $data): void
    {
        $manager = new ImageManager(new GdDriver());

        if ($data['images']['url'] instanceof TemporaryUploadedFile) {
            $data['images']['url'] = [$data['images']['url']];
        }

        foreach ($data['images']['url'] as $index => $url) {
            $manager->read(storage_path('app/livewire-tmp/'.$url->getFileName()));
            Storage::disk('local')->putFileAs('livewire-tmp/'.$data['shopSlug'], new File(storage_path('app/livewire-tmp/'.$url->getFileName())), $url->getFileName());
            unlink(storage_path('app/livewire-tmp/'.$url->getFileName()));
        }
    }

    protected function beforeCreate(): void
    {
        $this->shopIDs = $this->data['shops'];
    }

    protected function afterCreate(): void
    {
        $shopIDs = $this->shopIDs['id'];
        foreach ($shopIDs as $shopID) {
            $article = Article::with('variants')->whereId($this->record->id)->first();
            foreach ($article->variants as $variant) {
                $shopArticle = ShopArticle::create([
                    'shop_id' => $shopID,
                    'article_id' => $this->record->id,
                    'variant_id' => $variant->id,
                ]);

                $shopArticle->stock()->create([
                    'quantity' => 0,
                    'status' => 'out',
                    'limited_stock_below' => 5,
                ]);
            }
        }

        $manager = new ImageManager(new GdDriver());

        $article = $this->record;
        $shopSlug = $article->shops()->first()->slug;

        $storage = Storage::disk('local');
        $images = $storage->allFiles('livewire-tmp/'.$shopSlug);

        foreach ($images as $index => $url) {
            $baseImage = $manager->read(storage_path('app/'.$url));
            $baseImageExtension = $baseImage->exif('FILE')['MimeType'];
            switch ($baseImageExtension) {
                case 'image/jpeg':
                    $baseImageExtension = 'jpg';
                    break;
                case 'image/png':
                    $baseImageExtension = 'png';
                    break;
                default:
                    $baseImageExtension = '';
                    break;
            }
            $baseImageCopy = $baseImage->save(storage_path('app/s3/'.$shopSlug.'.'.$baseImageExtension));

            $uuid = Str::uuid();
            $fileName = $uuid;
            $tempFileName = auth()->user()->id.'_'.$uuid;

            $bigImage = $baseImageCopy;
            $mediumImage = $baseImageCopy;
            $smallImage = $baseImageCopy;

            $bigImage->scale(800);
            $bigImage->toWebp(100);
            $bigImage->save(storage_path('app/s3/big_'.$tempFileName.'.webp'));

            $mediumImage->scale(400);
            $mediumImage->toWebp(100);
            $mediumImage->save(storage_path('app/s3/medium_'.$tempFileName.'.webp'));

            $smallImage->scale(200);
            $smallImage->toWebp(100);
            $smallImage->save(storage_path('app/s3/small_'.$tempFileName.'.webp'));

            Storage::disk('s3')->putFileAs('web/big', new File(storage_path('app/s3/big_'.$tempFileName.'.webp')), $fileName.'.webp');
            Storage::disk('s3')->putFileAs('web/medium', new File(storage_path('app/s3/medium_'.$tempFileName.'.webp')), $fileName.'.webp');
            Storage::disk('s3')->putFileAs('web/small', new File(storage_path('app/s3/small_'.$tempFileName.'.webp')), $fileName.'.webp');

            unlink(storage_path('app/s3/'.$shopSlug.'.'.$baseImageExtension));
            unlink(storage_path('app/s3/big_'.$tempFileName.'.webp'));
            unlink(storage_path('app/s3/medium_'.$tempFileName.'.webp'));
            unlink(storage_path('app/s3/small_'.$tempFileName.'.webp'));

            $image = $article->images()->create([
                'url' => $fileName.'.webp',
            ]);
            ArticleImage::create([
                'image_id' => $image->id,
                'article_id' => $article->id,
            ]);
        }

        FacadesFile::deleteDirectory(storage_path('app/livewire-tmp/'.$shopSlug));

        $variants = $article->variants;

        foreach ($variants as $variant) {
            $variantImages = $storage->allFiles('livewire-tmp/'.$shopSlug.'-'.$variant->slug);

            foreach ($variantImages as $index => $url) {
                $variantBaseImage = $manager->read(storage_path('app/'.$url));
                $variantBaseImageExtension = $variantBaseImage->exif('FILE')['MimeType'];
                switch ($variantBaseImageExtension) {
                    case 'image/jpeg':
                        $variantBaseImageExtension = 'jpg';
                        break;
                    case 'image/png':
                        $variantBaseImageExtension = 'png';
                        break;
                    default:
                        $variantBaseImageExtension = '';
                        break;
                }
                $variantBaseImageCopy = $variantBaseImage->save(storage_path('app/s3/'.$shopSlug.'-'.$variant->slug.'.'.$variantBaseImageExtension));

                $variantUuid = Str::uuid();
                $variantFileName = $variantUuid;
                $variantTempFileName = auth()->user()->id.'_'.$variantUuid;

                $variantBigImage = $variantBaseImageCopy;
                $variantMediumImage = $variantBaseImageCopy;
                $variantSmallImage = $variantBaseImageCopy;

                $variantBigImage->scale(800);
                $variantBigImage->toWebp(100);
                $variantBigImage->save(storage_path('app/s3/big_'.$variantTempFileName.'.webp'));

                $variantMediumImage->scale(400);
                $variantMediumImage->toWebp(100);
                $variantMediumImage->save(storage_path('app/s3/medium_'.$variantTempFileName.'.webp'));

                $variantSmallImage->scale(200);
                $variantSmallImage->toWebp(100);
                $variantSmallImage->save(storage_path('app/s3/small_'.$variantTempFileName.'.webp'));

                Storage::disk('s3')->putFileAs('web/big', new File(storage_path('app/s3/big_'.$variantTempFileName.'.webp')), $variantFileName.'.webp');
                Storage::disk('s3')->putFileAs('web/medium', new File(storage_path('app/s3/medium_'.$variantTempFileName.'.webp')), $variantFileName.'.webp');
                Storage::disk('s3')->putFileAs('web/small', new File(storage_path('app/s3/small_'.$variantTempFileName.'.webp')), $variantFileName.'.webp');

                unlink(storage_path('app/s3/'.$shopSlug.'-'.$variant->slug.'.'.$variantBaseImageExtension));
                unlink(storage_path('app/s3/big_'.$variantTempFileName.'.webp'));
                unlink(storage_path('app/s3/medium_'.$variantTempFileName.'.webp'));
                unlink(storage_path('app/s3/small_'.$variantTempFileName.'.webp'));

                $variantImage = $variant->images()->create([
                    'url' => $variantFileName.'.webp',
                ]);
                VariantImage::create([
                    'image_id' => $variantImage->id,
                    'variant_id' => $variant->id,
                ]);
            }

            FacadesFile::deleteDirectory(storage_path('app/livewire-tmp/'.$shopSlug.'-'.$variant->slug));
        }
    }
}
