<?php


namespace Modules\Media\Services;


use PHPUnit\Util\Filesystem;
use Modules\Media\Entities\Media;
use Illuminate\Support\Facades\Storage;
use Modules\Media\Contracts\FileServiceContract;

class MediaFileService
{
    private static $file;
    private static $path;
    private static $dir;
    private static $isPrivate;

    public static function privateUpload($file)
    {
        self::$file = $file;
        self::$dir = 'app/private/';
        self::$isPrivate = true;
        return self::upload();
    }

    public static function publicUpload($file, $path = null)
    {
        self::$file = $file;
        self::$path = $path;
        self::$dir = 'app/public/';
        if ($path) self::$dir = self::$dir . $path  . '/';
        self::$isPrivate = false;
        return self::upload();
    }

    private static function upload()
    {
        $ext = self::normalizeExtension(self::$file);
        foreach (config('media.MediaTypeServices') as $key => $service) {
            if (in_array($ext, $service['extensions'])) {
                return self::uploadByHandler(new $service['handler'], $key);
            }
        }
    }

    public static function delete($media)
    {
        // foreach (config('media.MediaTypeServices') as $type => $service) {
        //     if ($media->type == $type) {
        //         return $service['handler']::delete($media);
        //     }
        // }
        Storage::delete('public\\' . $media);
    }

    private static function normalizeExtension($file): string
    {
        return strtolower($file->getClientOriginalExtension());
    }

    private static function filenameGenerator()
    {
        return uniqid();
    }

    private static function uploadByHandler($handler, $key)
    {
        // FileServiceContract
        $media = (object) array();
        $media->files = $handler::upload(self::$file, self::filenameGenerator(), self::$dir, self::$path);
        $media->type = $key;
        $media->filename = self::$file->getClientOriginalName();
        $media->is_private = self::$isPrivate;
        // $media->save();
        return $media;
    }

    public static function thumb(Media $media)
    {
        foreach (config('media.MediaTypeServices') as $type => $service) {
            if ($media->type == $type) {
                return $service['handler']::thumb($media);
            }
        }
    }

    public static function stream(Media $media)
    {

        //        dd($media);
        foreach (config('media.MediaTypeServices') as $type => $service) {
            if ($media->type == $type) {
                return $service['handler']::stream($media);
            }
        }
    }
}
