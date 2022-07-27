<?php


namespace App\Services;


use App\Contracts\FileServiceContract;
use App\Models\Media;

class VideoFileService extends DefaultFileService implements FileServiceContract
{
    public static function upload($file, string $filename, $dir): array
    {
        $ex = $file->getClientOriginalExtension();

        // $file->move(storage_path($dir), $filename . '.' . $ex);

        $file->storeAs($dir, $filename . '.' . $ex);


        return ['video' => $filename . '.' . $ex];
    }

    public static function thumb(Media $media)
    {
        return '/storage/' . $media->files['video'];
    }

    static function getFileName()
    {
        return (static::$media->is_private ? 'private/' : 'public/') . static::$media->files['video'];
    }
}
