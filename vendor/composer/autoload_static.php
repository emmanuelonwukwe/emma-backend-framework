<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit24e760508c66cb7e65ba57918fe1ab33
{
    public static $files = array (
        '272f0f3026f3b70359a03ce6aed20a0e' => __DIR__ . '/../..' . '/backend/helpers/error_handler.php',
        '12b9101a36c9daab90159a15701385b5' => __DIR__ . '/../..' . '/backend/helpers/authentication.php',
        '21325f5ca4de25c4cc1cfea9886ebaa6' => __DIR__ . '/../..' . '/backend/helpers/path_finder.php',
        '4c7b167ceefe5d614208447255d78539' => __DIR__ . '/../..' . '/backend/config/globals.php',
        'd5aa54321c2c9f5f1d98d7fcc5cac1c1' => __DIR__ . '/../..' . '/backend/helpers/msg.php',
        '37baae89f4d1f29cd3f05023fed36d86' => __DIR__ . '/../..' . '/backend/helpers/security.php',
        '50a0a8d30b02c41daca1dc754934b319' => __DIR__ . '/../..' . '/backend/helpers/request.php',
    );

    public static $prefixLengthsPsr4 = array (
        'U' => 
        array (
            'Utility\\' => 8,
        ),
        'P' => 
        array (
            'PHPMailer\\' => 10,
        ),
        'A' => 
        array (
            'App\\Providers\\' => 14,
            'App\\Models\\' => 11,
            'App\\Listeners\\' => 14,
            'App\\Http\\Exceptions\\' => 20,
            'App\\Http\\Controllers\\' => 21,
            'App\\Events\\' => 11,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Utility\\' => 
        array (
            0 => __DIR__ . '/../..' . '/backend/Utility',
        ),
        'PHPMailer\\' => 
        array (
            0 => __DIR__ . '/../..' . '/backend/phpmailer',
        ),
        'App\\Providers\\' => 
        array (
            0 => __DIR__ . '/../..' . '/backend/app/Providers',
        ),
        'App\\Models\\' => 
        array (
            0 => __DIR__ . '/../..' . '/backend/app/Models',
        ),
        'App\\Listeners\\' => 
        array (
            0 => __DIR__ . '/../..' . '/backend/app/Listeners',
        ),
        'App\\Http\\Exceptions\\' => 
        array (
            0 => __DIR__ . '/../..' . '/backend/app/Http/Exceptions',
        ),
        'App\\Http\\Controllers\\' => 
        array (
            0 => __DIR__ . '/../..' . '/backend/app/Http/Controllers',
        ),
        'App\\Events\\' => 
        array (
            0 => __DIR__ . '/../..' . '/backend/app/Events',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit24e760508c66cb7e65ba57918fe1ab33::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit24e760508c66cb7e65ba57918fe1ab33::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit24e760508c66cb7e65ba57918fe1ab33::$classMap;

        }, null, ClassLoader::class);
    }
}
