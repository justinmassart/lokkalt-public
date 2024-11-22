<?php

namespace App\Filament\Dashboard\Resources\ShopResource\Pages;

use App\Filament\Dashboard\Resources\ShopResource;
use App\Models\Franchise;
use App\Models\Image;
use App\Models\ShopImage;
use App\Models\ShopOwner;
use Aws\Rekognition\RekognitionClient;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Http\File;
use Illuminate\Support\Facades\File as FacadesFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Drivers\Gd\Driver as GdDriver;
use Intervention\Image\ImageManager;

class CreateShop extends CreateRecord
{
    protected static string $resource = ShopResource::class;

    protected ?bool $hasDatabaseTransactions = true;

    public function getTitle(): string
    {
        return ucfirst(__('filament.add_shop'));
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

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $franchise = Franchise::whereId($data['franchise'])->first();

        $data['name'] = $franchise->name;
        $data['franchise_id'] = $franchise->id;

        unset($data['franchise']);

        $data['slug'] = str()->slug($data['country'] . ' ' . $data['postal_code'] . ' ' . $data['name']);

        $images = $this->validateImages($data['images']);

        $data['images'] = $images;

        $imageModel = new Image();

        $imageModel->saveTemporaryImages($data);

        unset($data['images']);

        return $data;
    }

    protected function afterCreate(): void
    {
        $shop = $this->record;

        ShopOwner::create([
            'shop_id' => $shop->id,
            'user_id' => auth()->user()->id,
        ]);

        $manager = new ImageManager(new GdDriver());

        $storage = Storage::disk('local');
        $images = $storage->allFiles('livewire-tmp/' . $shop->slug);

        foreach ($images as $index => $url) {
            $baseImage = $manager->read(storage_path('app/' . $url));
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
            $baseImageCopy = $baseImage->save(storage_path('app/s3/' . $shop->slug . '.' . $baseImageExtension));

            $uuid = Str::uuid();
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

            unlink(storage_path('app/s3/' . $shop->slug . '.' . $baseImageExtension));
            unlink(storage_path('app/s3/big_' . $tempFileName . '.webp'));
            unlink(storage_path('app/s3/medium_' . $tempFileName . '.webp'));
            unlink(storage_path('app/s3/small_' . $tempFileName . '.webp'));

            $image = $shop->images()->create([
                'url' => $fileName . '.webp',
            ]);
            ShopImage::create([
                'image_id' => $image->id,
                'shop_id' => $shop->id,
            ]);
        }

        FacadesFile::deleteDirectory(storage_path('app/livewire-tmp/' . $shop->slug));

        session()->put('shop', $shop);
    }
}
