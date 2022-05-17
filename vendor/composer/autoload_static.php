<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit42a431f8396044d06dd0c989802f0328
{
    public static $prefixLengthsPsr4 = array (
        'G' => 
        array (
            'Genkiware\\PresetNote\\' => 21,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Genkiware\\PresetNote\\' => 
        array (
            0 => __DIR__ . '/../..' . '/',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit42a431f8396044d06dd0c989802f0328::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit42a431f8396044d06dd0c989802f0328::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit42a431f8396044d06dd0c989802f0328::$classMap;

        }, null, ClassLoader::class);
    }
}