<?php

namespace App\Models;

use Aws\Rekognition\RekognitionClient;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Drivers\Gd\Driver as GdDriver;
use Intervention\Image\ImageManager;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\WithFileUploads;

/**
 *
 *
 * @property string $id
 * @property string $url
 * @property bool $is_main_image
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Article|null $article
 * @property-read \App\Models\ArticleImage|null $articleImage
 * @property-read \App\Models\ShopImage|null $shopImage
 * @property-read \App\Models\Variant|null $variant
 * @property-read \App\Models\VariantImage|null $variantImage
 * @method static \Database\Factories\ImageFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Image newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Image newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Image query()
 * @method static \Illuminate\Database\Eloquent\Builder|Image whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Image whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Image whereIsMainImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Image whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Image whereUrl($value)
 * @mixin \Eloquent
 */
class Image extends Model
{
    use HasFactory, HasUuids, WithFileUploads;

    protected $guarded = [];

    protected $casts = [
        'url' => 'string',
        'is_main_image' => 'boolean',
    ];

    public function article(): BelongsTo
    {
        return $this->belongsTo(
            Article::class,
            'article_images',
            'article_id',
            'image_id'
        );
    }

    public function articleImage(): HasOne
    {
        return $this->hasOne(ArticleImage::class);
    }

    public function variantImage(): HasOne
    {
        return $this->hasOne(VariantImage::class);
    }

    public function shopImage(): HasOne
    {
        return $this->hasOne(ShopImage::class);
    }

    public function shop(): BelongsTo
    {
        return $this->belongsTo(
            Shop::class,
            'shop_images',
            'shop_id',
            'image_id'
        );
    }

    public function variant(): BelongsTo
    {
        return $this->belongsTo(Variant::class, 'variant_images', 'variant_id', 'image_id');
    }

    public function deleteArticleImage(): void
    {
        Storage::disk('s3')->delete('web/big/' . $this->url);
        Storage::disk('s3')->delete('web/medium/' . $this->url);
        Storage::disk('s3')->delete('web/small/' . $this->url);
        $this->articleImage->delete();
        $this->delete();
    }

    public function deleteVariantImage(): void
    {
        Storage::disk('s3')->delete('web/big/' . $this->url);
        Storage::disk('s3')->delete('web/medium/' . $this->url);
        Storage::disk('s3')->delete('web/small/' . $this->url);
        $this->variantImage->delete();
        $this->delete();
    }

    public function deleteShopImage(): void
    {
        Storage::disk('s3')->delete('web/big/' . $this->url);
        Storage::disk('s3')->delete('web/medium/' . $this->url);
        Storage::disk('s3')->delete('web/small/' . $this->url);
        $this->shopImage->delete();
        $this->delete();
    }

    public function saveTemporaryImages(array $data): void
    {
        $manager = new ImageManager(new GdDriver());

        if (is_string($data['images']['url'])) {
            $data['images']['url'] = [$data['images']['url']];
        }

        foreach ($data['images']['url'] as $index => $url) {
            $manager->read(storage_path('app/livewire-tmp/' . $url->getFileName()));
            Storage::disk('local')->putFileAs('livewire-tmp/' . $data['slug'], new File(storage_path('app/livewire-tmp/' . $url->getFileName())), $url->getFileName());
            unlink(storage_path('app/livewire-tmp/' . $url->getFileName()));
        }
    }

    public function validateImages(array $data): array
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
            $imageToAnalyze = file_get_contents(storage_path('app/livewire-tmp/' . $url->getFileName()));

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
                unlink(storage_path('app/livewire-tmp/' . $url->getFileName()));
                unset($data['url'][$index]);

                continue;
            }
        }

        return $data;
    }

    public function uploadImages(array $data, Model $model)
    {
        $returnedRules = [];

        $manager = new ImageManager(new GdDriver());

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

        if ($data['url'] instanceof TemporaryUploadedFile) {
            $data['url'] = [$data['url']];
        }

        foreach ($data['url'] as $index => $url) {
            $imageToAnalyze = file_get_contents(storage_path('app/livewire-tmp/' . $url->getFileName()));
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
                unlink(storage_path('app/livewire-tmp/' . $url->getFileName()));

                continue;
            }

            $baseImage = $manager->read(storage_path('app/livewire-tmp/' . $url->getFileName()));
            $baseImageCopy = $baseImage->save(storage_path('app/s3/' . $url->getFileName()));
            unlink(storage_path('app/livewire-tmp/' . $url->getFileName()));

            $uuid = Str::orderedUuid();
            $fileName = $uuid;
            $tempFileName = auth()->user()->id . '_' . $uuid;

            $bigImage = $baseImageCopy;
            $mediumImage = $baseImageCopy;
            $smallImage = $baseImageCopy;

            $bigImage->scale(800);
            $bigImage->toWebp(100);
            $bigImage->save(storage_path('app/s3/big_' . $tempFileName . '.webp'));

            $mediumImage->scale(400);
            $mediumImage->toWebp(100);
            $mediumImage->save(storage_path('app/s3/medium_' . $tempFileName . '.webp'));

            $smallImage->scale(200);
            $smallImage->toWebp(100);
            $smallImage->save(storage_path('app/s3/small_' . $tempFileName . '.webp'));

            Storage::disk('s3')->putFileAs('web/big', new File(storage_path('app/s3/big_' . $tempFileName . '.webp')), $fileName . '.webp');
            Storage::disk('s3')->putFileAs('web/medium', new File(storage_path('app/s3/medium_' . $tempFileName . '.webp')), $fileName . '.webp');
            Storage::disk('s3')->putFileAs('web/small', new File(storage_path('app/s3/small_' . $tempFileName . '.webp')), $fileName . '.webp');

            unlink(storage_path('app/s3/' . $url->getFileName()));
            unlink(storage_path('app/s3/big_' . $tempFileName . '.webp'));
            unlink(storage_path('app/s3/medium_' . $tempFileName . '.webp'));
            unlink(storage_path('app/s3/small_' . $tempFileName . '.webp'));

            $image = $this->create([
                'url' => $fileName . '.webp',
            ]);

            $pivotImage = $model->pivotImages()->create([
                'image_id' => $image->id,
            ]);
        }

        $this->cleanupOldUploads();

        return $returnedRules;
    }

    public function cleanupOldUploads()
    {
        $storage = Storage::disk('local');

        foreach ($storage->allFiles('livewire-tmp') as $filePathname) {
            if (!$storage->exists($filePathname)) {
                continue;
            }

            $timestamp = now()->subHours(1)->timestamp;
            if ($timestamp > $storage->lastModified($filePathname)) {
                $storage->delete($filePathname);
            }
        }

        foreach ($storage->allFiles('s3') as $filePathname) {
            if (!$storage->exists($filePathname)) {
                continue;
            }

            $timestamp = now()->subHours(1)->timestamp;
            if ($timestamp > $storage->lastModified($filePathname)) {
                $storage->delete($filePathname);
            }
        }
    }
}
