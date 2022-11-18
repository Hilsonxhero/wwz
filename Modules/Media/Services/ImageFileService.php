<?php


namespace Modules\Media\Services;


use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Modules\Media\Contracts\FileServiceContract;
use Modules\Media\Entities\Media;

class ImageFileService extends DefaultFileService
{
    // implements FileServiceContract
    public static $sizes = ['300', '600'];

    public static function upload($file, string $filename, $dir, $route)
    {
        $ex = $file->getClientOriginalExtension();
        $file->move(storage_path($dir), $filename . '.' . $ex);
        $path = $dir . $filename . '.' . $ex;
        // return $path;
        return self::resize(storage_path($path), $filename, $dir, $ex, $route);
    }

    private static function resize($img, $filename, $dir, $ex, $path)
    {

        $imgs['original'] =  $path . '/' . $filename . '.' . $ex;
        if ($ex !== 'svg') {
            $img = Image::make($img);
            foreach (self::$sizes as $size) {
                $imgs[$size] = $filename . '_' . $size . '.' . $ex;
                $img->resize($size, null, function ($aspect) {
                    $aspect->aspectRatio();
                })->save(storage_path($dir) . $filename . '_' . $size . '.' . $ex);
            }
        }

        return $imgs;
    }


    public static function thumb($media)
    {
        return '/storage/' . $media->files[300];
    }
    public static function original($media)
    {
        return '/storage/' . $media->files['original'];
    }

    static function getFileName()
    {
        return (static::$media->is_private ? 'private/' : 'public/') . static::$media->files['original'];
    }
}
