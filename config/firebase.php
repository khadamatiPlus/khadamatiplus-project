<?php

declare(strict_types=1);

return [

/*
 * ------------------------------------------------------------------------
 * Default Firebase project
 * ------------------------------------------------------------------------
 */
    'default' => env('FIREBASE_PROJECT', 'app'),
    'credentials' => [
        'type' => 'service_account',
        'project_id' => 'khadamat-services',
        'private_key_id' => '69c8c08e6726bad997ea652443ef75e5a011cdbd',
        'private_key' => "-----BEGIN PRIVATE KEY-----\nMIIEvAIBADANBgkqhkiG9w0BAQEFAASCBKYwggSiAgEAAoIBAQDgKWDCfgfIbbhr\nA7ZJW64AmJMHBo/R6ToNLRtNSBvY4BEJYreBePwIc3zV11Br6Lpbr5VsEK7cfuoH\nhG2VMqTRbpetaBBur4jHNZGI+clBsal6/dJOG2Aq5ZWHsykVIbwkjrLcRNwGllqp\nSTMgYCbGewLeUxKz9GltVAsGB7TsWASFUnhINclwnsKYphyFuxkc8DE7RlaBexT/\ng9F4h8b/zpc24aAq4s0cJXpN4Sqg8BgAssgniWoWdztmCuL8B5vbaIXfBPX0GJBW\naXv4MHyZDeCEhkhy47eH+uqGEazLm8cjmRGsgSdFGDCN+z7TO9YoqgLWI6P4utVV\nWhft5DP3AgMBAAECggEAWSDasjp+SbkmnJZLQnvLgb0oqKB5StNbG6YVtvK279KX\ns42mvTonrAyPVE9aUK2me5Ii7WbswAtOblK/Z5VNZ2B7up0n+3qb/elUxova8936\nL0cA+cF0yVmjzOL1sMtLDZYXk/FlMtFRlwHd7D+O61g52cBaLWc/DBNglrM1wsq1\n7Bas7CY7A43IqIMdOAQNwX/d/mtlLoYJIJp6T3CIExcATrxka/PWC+E4ljQUaFzP\ndI5eb22MGL5YrZhRvkdFruixIbuN17lELbYsqifA2uVzTrqBKyLZpqdFR9eEFkee\nKvsS09dtqnaghxa02FFlPdjai4pDQF2mhcgrAXNLwQKBgQDzQJ0XHVy4MEJuMfNC\nNb2M4iMz+hm0EjQKX/v6Tg1nEfTx3CSRKRfK6LyQNmPBLrUH0AYVxCYu9q3kYXRl\nd+/2nYqXQuD9/8t2yHbvtCkeIECk63VLqTmmGQ6srx1UVIPxHqjhNhz5eLkhqbcs\nE51ZFjyhICvpDyX9sYY/34+/hwKBgQDr6KZHk7NUtO1fZsKD6DHVqRJadtIZJs1N\njyrV1KUsg0QqjdZav51JXtIMgHwb7bqkgMh9W+bXU6JsBdDfVwI/qEJ+GTxRNGxD\nZWi/3AFx5dkFF15dOdtgx1n/pCzL3Nr/+4i46YJdIfAD0A4eggTNcRKzOn0dqNSw\n8CKqeaqkEQKBgCJYHBXYYm/q+GYgjUzq04FhqEC9bgWbkFdqp+P2QzQrN98yCsOn\n/qD6bS0bUMhEtPCOcV1/XTjCQVj+XXW6ElpKcffbTHLwO6TgttIvKKMFQLtINz5g\nfTAzV3wQGV7/s8VGY+ewQTUD03eqQ59ogY3Dcvn41dpI9j80YlUAVoZfAoGAEEGD\n08LVxKyC/uPx220Qogx4e0tcxubplsKazQlOKHcTJ2uGrdIuYHPsPXhNFQ+YoOVO\n63R9v7C/rHnqHrm8Ke7KRi4u/dmC7FH8mdvrLZINFlC56+qkt1KFXRxPCe06GhGX\nJkbQ+OHxzBF2J5wGALId+8O4A2cy4M5rVRv1KkECgYBeHopY+8teAjdQBXLU3+Ew\nrWnJPKVGMUknLVSXzKJb/5l8X4cE3erZvqkByXx/H7NAgvxgfBZoHEryrwQcbXlc\nVFmU1uTmU5TFqjmsu0R9dO8iYnjrWI2qOTXjjlu/AN7d24nZEtXB1uiZVcH3ebrn\nvMi43rSzC/8CSIrVf4DgCg==\n-----END PRIVATE KEY-----\n",
        'client_email' => 'firebase-adminsdk-pyzyo@khadamat-services.iam.gserviceaccount.com',
        'client_id' => '102408090052003763615',
        'auth_uri' => 'https://accounts.google.com/o/oauth2/auth',
        'token_uri' => 'https://oauth2.googleapis.com/token',
        'auth_provider_x509_cert_url' => 'https://www.googleapis.com/oauth2/v1/certs',
        'client_x509_cert_url' => 'https://www.googleapis.com/robot/v1/metadata/x509/firebase-adminsdk-pyzyo@khadamat-services.iam.gserviceaccount.com',
        'universe_domain' => 'googleapis.com'
    ],

    /*
     * ------------------------------------------------------------------------
     * Firebase project configurations
     * ------------------------------------------------------------------------
     */
    'projects' => [
        'app' => [
            /*
             * ------------------------------------------------------------------------
             * Credentials / Service Account
             * ------------------------------------------------------------------------
             *
             * In order to access a Firebase project and its related services using a
             * server SDK, requests must be authenticated. For server-to-server
             * communication this is done with a Service Account.
             *
             * If you don't already have generated a Service Account, you can do so by
             * following the instructions from the official documentation pages at
             *
             * https://firebase.google.com/docs/admin/setup#initialize_the_sdk
             *
             * Once you have downloaded the Service Account JSON file, you can use it
             * to configure the package.
             *
             * If you don't provide credentials, the Firebase Admin SDK will try to
             * auto-discover them
             *
             * - by checking the environment variable FIREBASE_CREDENTIALS
             * - by checking the environment variable GOOGLE_APPLICATION_CREDENTIALS
             * - by trying to find Google's well known file
             * - by checking if the application is running on GCE/GCP
             *
             * If no credentials file can be found, an exception will be thrown the
             * first time you try to access a component of the Firebase Admin SDK.
             *
             */
            'credentials' => [
                'file' => env('FIREBASE_CREDENTIALS', env('GOOGLE_APPLICATION_CREDENTIALS')),

                /*
                 * If you want to prevent the auto discovery of credentials, set the
                 * following parameter to false. If you disable it, you must
                 * provide a credentials file.
                 */
                'auto_discovery' => true,
            ],

            /*
             * ------------------------------------------------------------------------
             * Firebase Auth Component
             * ------------------------------------------------------------------------
             */

            'auth' => [
                'tenant_id' => env('FIREBASE_AUTH_TENANT_ID'),
            ],

            /*
             * ------------------------------------------------------------------------
             * Firebase Realtime Database
             * ------------------------------------------------------------------------
             */

            'database' => [
                /*
                 * In most of the cases the project ID defined in the credentials file
                 * determines the URL of your project's Realtime Database. If the
                 * connection to the Realtime Database fails, you can override
                 * its URL with the value you see at
                 *
                 * https://console.firebase.google.com/u/1/project/_/database
                 *
                 * Please make sure that you use a full URL like, for example,
                 * https://my-project-id.firebaseio.com
                 */
                'url' => env('FIREBASE_DATABASE_URL'),

                /*
                 * As a best practice, a service should have access to only the resources it needs.
                 * To get more fine-grained control over the resources a Firebase app instance can access,
                 * use a unique identifier in your Security Rules to represent your service.
                 *
                 * https://firebase.google.com/docs/database/admin/start#authenticate-with-limited-privileges
                 */
                // 'auth_variable_override' => [
                //     'uid' => 'my-service-worker'
                // ],
            ],

            'dynamic_links' => [
                /*
                 * Dynamic links can be built with any URL prefix registered on
                 *
                 * https://console.firebase.google.com/u/1/project/_/durablelinks/links/
                 *
                 * You can define one of those domains as the default for new Dynamic
                 * Links created within your project.
                 *
                 * The value must be a valid domain, for example,
                 * https://example.page.link
                 */
                'default_domain' => env('FIREBASE_DYNAMIC_LINKS_DEFAULT_DOMAIN'),
            ],

            /*
             * ------------------------------------------------------------------------
             * Firebase Cloud Storage
             * ------------------------------------------------------------------------
             */

            'storage' => [
                /*
                 * Your project's default storage bucket usually uses the project ID
                 * as its name. If you have multiple storage buckets and want to
                 * use another one as the default for your application, you can
                 * override it here.
                 */

                'default_bucket' => env('FIREBASE_STORAGE_DEFAULT_BUCKET'),
            ],

            /*
             * ------------------------------------------------------------------------
             * Caching
             * ------------------------------------------------------------------------
             *
             * The Firebase Admin SDK can cache some data returned from the Firebase
             * API, for example Google's public keys used to verify ID tokens.
             *
             */

            'cache_store' => env('FIREBASE_CACHE_STORE', 'file'),

            /*
             * ------------------------------------------------------------------------
             * Logging
             * ------------------------------------------------------------------------
             *
             * Enable logging of HTTP interaction for insights and/or debugging.
             *
             * Log channels are defined in config/logging.php
             *
             * Successful HTTP messages are logged with the log level 'info'.
             * Failed HTTP messages are logged with the the log level 'notice'.
             *
             * Note: Using the same channel for simple and debug logs will result in
             * two entries per request and response.
             */

            'logging' => [
                'http_log_channel' => env('FIREBASE_HTTP_LOG_CHANNEL'),
                'http_debug_log_channel' => env('FIREBASE_HTTP_DEBUG_LOG_CHANNEL'),
            ],

            /*
             * ------------------------------------------------------------------------
             * HTTP Client Options
             * ------------------------------------------------------------------------
             *
             * Behavior of the HTTP Client performing the API requests
             */
            'http_client_options' => [
                /*
                 * Use a proxy that all API requests should be passed through.
                 * (default: none)
                 */
                'proxy' => env('FIREBASE_HTTP_CLIENT_PROXY'),

                /*
                 * Set the maximum amount of seconds (float) that can pass before
                 * a request is considered timed out
                 * (default: indefinitely)
                 */
                'timeout' => env('FIREBASE_HTTP_CLIENT_TIMEOUT'),
            ],

            /*
             * ------------------------------------------------------------------------
             * Debug (deprecated)
             * ------------------------------------------------------------------------
             *
             * Enable debugging of HTTP requests made directly from the SDK.
             */
            'debug' => env('FIREBASE_ENABLE_DEBUG', false),
        ],
    ],
];
