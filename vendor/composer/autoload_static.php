<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit927ba6ccd5e26605096d3bf366219b76
{
    public static $files = array (
        'bb4b35e723e62120e88a90f76b1d1d42' => __DIR__ . '/..' . '/eeshiro/file-upload/src/FileUpload.php',
        '50f72c76c5c781b097954a286bc7cd7e' => __DIR__ . '/..' . '/eeshiro/decimal-to-word/src/DecimalToWord.php',
    );

    public static $prefixLengthsPsr4 = array (
        'F' => 
        array (
            'FileUpload\\' => 11,
        ),
        'D' => 
        array (
            'DecimalToWord\\' => 14,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'FileUpload\\' => 
        array (
            0 => __DIR__ . '/..' . '/eeshiro/file-upload/src/FileUpload',
        ),
        'DecimalToWord\\' => 
        array (
            0 => __DIR__ . '/..' . '/eeshiro/decimal-to-word/src/DecimalToWord',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit927ba6ccd5e26605096d3bf366219b76::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit927ba6ccd5e26605096d3bf366219b76::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit927ba6ccd5e26605096d3bf366219b76::$classMap;

        }, null, ClassLoader::class);
    }
}