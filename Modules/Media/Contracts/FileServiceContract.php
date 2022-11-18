<?php


namespace Modules\Media\Contracts;


use Modules\Media\Entities\Media;

interface FileServiceContract
{
    public static function upload($file, string $filename, $dir);

    public static function delete($media);

    public static function thumb(Media $media);
    public static function stream(Media $media);
}
