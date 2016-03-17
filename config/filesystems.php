<?php

/**
 * File system config
 */

return [
    'default' => 'local',
    'disks' => [
        'local' => [
            'driver' => 'local',
            'root' => storage_path('app')
        ]
    ]
];
