<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitf754bd5a299fc10c0d44b4deecc8a919
{
    public static $files = array (
        'a0edc8309cc5e1d60e3047b5df6b7052' => __DIR__ . '/..' . '/guzzlehttp/psr7/src/functions_include.php',
    );

    public static $prefixLengthsPsr4 = array (
        'P' => 
        array (
            'Psr\\Http\\Message\\' => 17,
        ),
        'I' => 
        array (
            'Intervention\\Image\\' => 19,
        ),
        'G' => 
        array (
            'GuzzleHttp\\Psr7\\' => 16,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Psr\\Http\\Message\\' => 
        array (
            0 => __DIR__ . '/..' . '/psr/http-message/src',
        ),
        'Intervention\\Image\\' => 
        array (
            0 => __DIR__ . '/..' . '/intervention/image/src/Intervention/Image',
        ),
        'GuzzleHttp\\Psr7\\' => 
        array (
            0 => __DIR__ . '/..' . '/guzzlehttp/psr7/src',
        ),
    );

    public static $prefixesPsr0 = array (
        'N' => 
        array (
            'NMC' => 
            array (
                0 => __DIR__ . '/..' . '/nmcteam/image-with-text/src',
            ),
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitf754bd5a299fc10c0d44b4deecc8a919::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitf754bd5a299fc10c0d44b4deecc8a919::$prefixDirsPsr4;
            $loader->prefixesPsr0 = ComposerStaticInitf754bd5a299fc10c0d44b4deecc8a919::$prefixesPsr0;

        }, null, ClassLoader::class);
    }
}
