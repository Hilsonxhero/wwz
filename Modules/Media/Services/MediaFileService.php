<?php


namespace Modules\Media\Services;


use Modules\Media\Contracts\FileServiceContract;
use Modules\Media\Entities\Media;
use PHPUnit\Util\Filesystem;

class MediaFileService
{
    private static $file;
    private static $dir;
    private static $isPrivate;

    public static function privateUpload($file)
    {
        self::$file = $file;
        self::$dir = 'app/private/';
        self::$isPrivate = true;
        return self::upload();
    }

    public static function publicUpload($file)
    {
        self::$file = $file;
        self::$dir = 'app/public/';
        self::$isPrivate = false;
        return self::upload();
    }

    private static function upload()
    {
        $ext = self::normalizeExtension(self::$file);
        foreach (config('MediaFile.MediaTypeServices') as $key => $service) {
            if (in_array($ext, $service['extensions'])) {
                return self::uploadByHandler(new $service['handler'], $key);
            }
        }
    }

    public static function delete($media)
    {
        foreach (config('MediaFile.MediaTypeServices') as $type => $service) {
            if ($media->type == $type) {
                return $service['handler']::delete($media);
            }
        }
    }

    private static function normalizeExtension($file): string
    {
        return strtolower($file->getClientOriginalExtension());
    }

    private static function filenameGenerator()
    {
        return uniqid();
    }

    private static function uploadByHandler(FileServiceContract $handler, $key): Media
    {
        $media = new Media();
        $media->files = $handler::upload(self::$file, self::filenameGenerator(), self::$dir);
        $media->type = $key;
        $media->user_id = auth()->id();
        $media->filename = self::$file->getClientOriginalName();
        $media->is_private = self::$isPrivate;
        $media->save();
        return $media;
    }

    public static function thumb(Media $media)
    {

        foreach (config('MediaFile.MediaTypeServices') as $type => $service) {
            if ($media->type == $type) {
                return $service['handler']::thumb($media);
            }
        }
    }

    public static function stream(Media $media)
    {

        //        dd($media);
        foreach (config('MediaFile.MediaTypeServices') as $type => $service) {
            if ($media->type == $type) {
                return $service['handler']::stream($media);
            }
        }
    }
}
