<?php

namespace App\Services;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Intervention\Image\Encoders\WebpEncoder;
use Intervention\Image\Laravel\Facades\Image;

class ImageService
{
    
    public function compressAndStore(UploadedFile $file):string {
        $image = Image::decode($file);
        $image->scaleDown(
            width:1200,
            height:1200
        );
        $filename= Str::uuid(). '.webp';

        $directory=public_path('image_uploads');
        File::ensureDirectoryExists($directory);

        $image->encode(new WebpEncoder(quality: 80))
      ->save($directory . DIRECTORY_SEPARATOR . $filename);

        return $filename;   
    }
}
