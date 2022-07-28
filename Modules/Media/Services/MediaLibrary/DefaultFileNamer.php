<?php

namespace Modules\Media\Services\MediaLibrary;

use Illuminate\Support\Str;

use Spatie\MediaLibrary\Conversions\Conversion;
use Spatie\MediaLibrary\Support\FileNamer\FileNamer;

class DefaultFileNamer extends FileNamer
{

    public function originalFileName(string $fileName): string
    {
        // return pathinfo($fileName, PATHINFO_FILENAME);
        return  Str::random(20);
    }

    public function conversionFileName(string $fileName, Conversion $conversion): string
    {
        $strippedFileName = pathinfo($fileName, PATHINFO_FILENAME);

        return "{$strippedFileName}-{$conversion->getName()}";
    }

    public function responsiveFileName(string $fileName): string
    {
        return pathinfo($fileName, PATHINFO_FILENAME);
    }
}
