<?php
/*
 * Local configuration file to provide any overrides to your app.php configuration.
 * Copy and save this file as app_local.php and make changes as required.
 * Note: It is not recommended to commit files with credentials such as app_local.php
 * into source code version control.
 */
return [

    /*
     * Security and encryption configuration
     *
     * - salt - A random string used in security hashing methods.
     *   The salt value is also used as the encryption key.
     *   You should treat it as extremely sensitive data.
     */
    'Security' => [
        'salt' => env('SECURITY_SALT', 'f5ef08e718a6c8f34a408242b8906acc2bed11eed61491589f8b99217bde2954'),
        'cookieKey' => env('SECURITY_COOKIE_KEY', 'kmn8b8906acc2bed11eed6149f5ef08e718a6c8f34a401589f8b99217bde9907')
    ],

    /*
     * Connection information used by the ORM to connect
     * to your application's datastores.
     *
     * See app.php for more configuration options.
     */
    'Datasources' => [
        'default' => [
            'host' => 'dev.webmotion.it',
            'username' => '',
            'password' => '',
            'database' => '',
            'url' => env('DATABASE_URL', null),
            'log' => false, // da abilitare per loggare le query nel queriesLog
            'quoteIdentifiers' => false, // serve a quotare parole riservate di mySQL usate come campi del database. Evitare di usarle e non attivarlo se possibile perché riduce performance
            //'cacheMetadata' => true,
        ],
    ],

    /*
     * Email configuration.
     *
     * Host and credential configuration in case you are using SmtpTransport
     *
     * See app.php for more configuration options.
     */
    'EmailTransport' => [
        'default' => [
            'host' => 'localhost',
            'port' => 25,
            'username' => null,
            'password' => null,
            'client' => null,
            'url' => env('EMAIL_TRANSPORT_DEFAULT_URL', null),
        ],
        'smtp' => [
            'className' => 'Smtp',
            'host' => 'host',
            'port' => 465,
            'timeout' => 30,
            'username' => 'username',
            'password' => 'password',
            'client' => null,
            'tls' => false,
            'url' => env('EMAIL_TRANSPORT_DEFAULT_URL', null),
        ],
    ],
];
