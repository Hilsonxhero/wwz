<?php


namespace Modules\Media\Services;

use Modules\Media\Contracts\FileServiceContract;
use Modules\Media\Entities\Media;

class ZipFileService extends DefaultFileService implements FileServiceContract
{
    public static function upload($file, string $filename, $dir): array
    {
        $ex = $file->getClientOriginalExtension();
        $file->move(storage_path($dir), $filename . '.' . $ex);
        return ['zip' =>  $filename . '.' . $ex];
    }
    public static function thumb(Media $media)
    {
        return asset('panel/assets/img/zip-icon.svg');
    }
    public static function getFileName()
    {
        return (static::$media->is_private ? 'private/' : 'public/') . static::$media->files['zip'];
    }
}
