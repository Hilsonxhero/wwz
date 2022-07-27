<?php

namespace App\Services;

use App\Contracts\FileServiceContract;
use App\Models\Media;
use App\Services\DefaultFileService;
use Intervention\Image\Facades\Image;

class ImageFileService extends DefaultFileService implements FileServiceContract
{
    public static $sizes = ['410'];

    public static function upload($file, string $filename, $dir): array
    {
        $ex = $file->getClientOriginalExtension();

        // $file->move(storage_path($dir), $filename . '.' . $ex);

        $path =  $filename . '.' . $ex;

        $file->storeAs($dir, $filename . '.' . $ex);

        // Storage::put('test', $file);

         return self::resize($file,storage_path($path), $filename, $dir, $ex);

//        return ['original' => $path];
    }

    private static function resize($file,$img, $filename, $dir, $ex)
    {

//        $img = Image::make($img);
        $img = Image::make($file->getRealPath());
//        dd("here");
        $imgs['original'] = $filename . '.' . $ex;
//        $imgs = array();
        foreach (self::$sizes as $size) {
            $imgs[$size] = $filename . '_' . $size . '.' . $ex;
            $img->resize($size, null, function ($aspect) {
                $aspect->aspectRatio();
            })->save(storage_path('app/' . $dir) . $filename . '_' . $size . '.' . $ex);
        }
        return $imgs;
    }

    public static function thumb(Media $media)
    {

        return '/storage/' . $media->files['410'];
    }

    public static function original(Media $media)
    {

        return '/storage/' . $media->files['original'];
    }

    public static function getFileName()
    {
        return (static::$media->is_private ? 'private/' : 'public/') . static::$media->files['original'];
    }
}
