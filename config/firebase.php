<?php

declare(strict_types=1);

return [
    'default' => env('FIREBASE_PROJECT', 'app'),

    'projects' => [
        'app' => [
            /*
             * Credentials / Service Account
             */
            'credentials' => storage_path('app/firebase/capstone-eventtabula-firebase-adminsdk-xqda4-58b3af6774.json'),
            /*
             * Project ID
             */
            'project_id' => env('FIREBASE_PROJECT_ID', 'capstone-eventtabula'),

            /*
             * Firebase Database URL
             */
            'database' => [
                'url' => env('FIREBASE_DATABASE_URL', 'https://capstone-eventtabula-default-rtdb.firebaseio.com/'),
            ],

            'auth' => [
                'tenant_id' => null,
            ],

            'cache_store' => 'file',
        ],
    ],
];