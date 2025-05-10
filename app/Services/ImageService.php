<?php

namespace App\Services;

use Exception;

class ImageService
{

    public function storeImage($model, $images, $collection, $replace = true)
    {
        try {
            if (empty($images)) return;

            $images = collect(is_array($images) ? $images : [$images]);

            if ($replace) {
                $model->clearMediaCollection($collection);
            }

            $images->each(function ($image) use ($model, $collection) {
                if ($image instanceof \Illuminate\Http\UploadedFile) {
                    $mediaModel = $model->addMedia($image)
                        ->preservingOriginal()
                        ->toMediaCollection($collection);

                } else {
                    if (str_contains($image, config('app.url'))) {
                        $image = str_replace(config('app.url'), '', $image);
                    }

                    $imagePath = str_starts_with($image, '/') || str_starts_with($image, 'C:\\')
                        ? $image
                        : storage_path('app/public') . $image;

                    if (!file_exists($imagePath)) {
                        throw new \Exception("File not found: " . $imagePath);
                    }

                    $mediaModel = $model->addMedia($imagePath)
                        ->preservingOriginal()
                        ->toMediaCollection($collection);
                }

                $mediaModel->save();
            });
        } catch (\Exception $e) {
            throw $e;
        }
    }
}
