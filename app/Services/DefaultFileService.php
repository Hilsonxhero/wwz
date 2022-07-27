<?php


namespace App\Services;


use Illuminate\Support\Facades\Storage;
use App\Models\Media;

abstract class DefaultFileService
{

    public static $media;

    public static function delete($media)
    {

        foreach ($media->files as $file) {
            if ($media->is_private) {
                Storage::delete('private\\' . $file);
            } else {
                Storage::delete('public\\' . $file);
            }

        }
    }

    abstract static function getFileName();


    public static function stream(Media $media)
    {
        self::$media = $media;
        $stream = Storage::readStream(static::getFileName());
        return response()->stream(function () use ($stream) {
            while (ob_get_level() > 0) ob_get_flush();
            fpassthru($stream);
        }, 200,
            [
                "Content-Type" => Storage::mimeType(static::getFileName()),
                "Content-disposition" => "attachment;filename ='" . static::$media->filename . "'"
            ]
        );
    }
}
