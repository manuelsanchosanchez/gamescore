<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit44f7bf89aa8f6ace2c77845ea9650c10
{
    public static $prefixLengthsPsr4 = array (
        'G' => 
        array (
            'Ghunti\\HighchartsPHP\\' => 21,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Ghunti\\HighchartsPHP\\' => 
        array (
            0 => __DIR__ . '/..' . '/ghunti/highcharts-php/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit44f7bf89aa8f6ace2c77845ea9650c10::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit44f7bf89aa8f6ace2c77845ea9650c10::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit44f7bf89aa8f6ace2c77845ea9650c10::$classMap;

        }, null, ClassLoader::class);
    }
}
