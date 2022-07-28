<?php


namespace Modules\Media\Services;


use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Modules\Media\Contracts\FileServiceContract;
use Modules\Media\Entities\Media;

class ImageFileService extends DefaultFileService implements FileServiceContract
{
    public static $sizes = ['300', '600'];

    public static function upload($file, string $filename, $dir): array
    {
        $ex = $file->getClientOriginalExtension();
        $file->move(storage_path($dir), $filename . '.' . $ex);
        $path = $dir . $filename . '.' . $ex;
        return self::resize(storage_path($path), $filename, $dir, $ex);
    }

    private static function resize($img, $filename, $dir, $ex)
    {
        $img = Image::make($img);
        $imgs['original'] = $filename . '.' . $ex;
        foreach (self::$sizes as $size) {
            $imgs[$size] = $filename . '_' . $size . '.' . $ex;
            $img->resize($size, null, function ($aspect) {
                $aspect->aspectRatio();
            })->save(storage_path($dir) . $filename . '_' . $size . '.' . $ex);
        }
        return $imgs;
    }


    public static function thumb(Media $media)
    {
        return '/storage/' . $media->files[300];
    }

    static function getFileName()
    {
        return (static::$media->is_private ? 'private/' : 'public/') . static::$media->files['original'];
    }
}
