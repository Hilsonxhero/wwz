<?php

// return [
//     'name' => 'Media'
// ];


return [
    'name' => 'Media',
    "MediaTypeServices" => [
        "image" => [
            "extensions" => [
                "png", "jpg", "jpeg", "svg"
            ],
            "handler" => \Modules\Media\Services\ImageFileService::class
        ],
        "video" => [
            "extensions" => [
                "mp4", "avi"
            ],
            "handler" => \Modules\Media\Services\VideoFileService::class
        ],
        "zip" => [
            "extensions" => [
                "rar", "zip"
            ],
            "handler" => \Modules\Media\Services\ZipFileService::class
        ]
    ]
];
