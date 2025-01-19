<?php
return [
    'paths' => [
        'api/*',
        'hrm/*',
        'upload/*', // Allow access to the upload folder for images
        'storage/*', // Allow access to storage if using symbolic links
        'sanctum/csrf-cookie'
    ],
    'allowed_methods' => ['*'],

    'allowed_origins' => ['*'], // Change '*' to specific domains if necessary

    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 0,

    'supports_credentials' => true,
];

