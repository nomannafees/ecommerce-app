<?php


namespace App\Services;

use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;


class ImageService
{
    public function processAndStore($file, string $folder = 'brands', int $width = 800, int $height = 800, int $quality = 80): string
    {
        $filename = time() . '_' . uniqid() . '.webp';

        // Ensure folder exists in public/storage
        $destinationPath = public_path('storage/' . $folder);
        if (!file_exists($destinationPath)) {
            mkdir($destinationPath, 0777, true);
        }

        // Intervention Image v3 processing
        $manager = new ImageManager(new Driver());
        $image = $manager->read($file->getRealPath());

        // Fit to 1:1 and convert to WebP
        $encoded = $image->cover($width, $height)->toWebp($quality);

        // Save file
        $encoded->save($destinationPath . '/' . $filename);

        // Return relative path for database
        return $folder . '/' . $filename;
    }

    public function processAndStoreWithoutCrop($file, string $folder = 'brands', int $width = 800, int $height = 800, int $quality = 80): string
    {
        $filename = time() . '_' . uniqid() . '.webp';

        // Laravel Storage disk ('public') par folder ensure karna zyada behtar hai
        $destinationPath = public_path('storage/' . $folder);
        if (!file_exists($destinationPath)) {
            mkdir($destinationPath, 0777, true);
        }

        // Intervention Image v3 processing
        $manager = new ImageManager(new Driver());
        $image = $manager->read($file->getRealPath());

        // Scale down image to fit within max width and height without cropping or distorting
        $image->scale(width: $width, height: $height);

    // Convert to WebP and set quality
    $encoded = $image->toWebp($quality);

    // Save file
    $encoded->save($destinationPath . '/' . $filename);

    // Return relative path for database
    return $folder . '/' . $filename;
}
}