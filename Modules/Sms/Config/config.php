<?php

use Modules\Sms\Drivers\Melipayamak\Melipayamak;



return [
    'name' => 'Sms',
    'default' => 'melipayamak',

    'drivers' => [
        'melipayamak' => [
            'operator' => 'melipayamak',
            'username' => config('services.sms.melipayamak.username'),
            'password' => config('services.sms.melipayamak.password'),
        ],
    ],

    'map' => [
        'melipayamak' => Melipayamak::class,
    ]
];
