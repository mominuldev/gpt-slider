<?php

// autoload_real.php @generated by Composer

class ComposerAutoloaderInitdcaf1f150bc8a3b391a400f5763b2955
{
    private static $loader;

    public static function loadClassLoader($class)
    {
        if ('Composer\Autoload\ClassLoader' === $class) {
            require __DIR__ . '/ClassLoader.php';
        }
    }

    /**
     * @return \Composer\Autoload\ClassLoader
     */
    public static function getLoader()
    {
        if (null !== self::$loader) {
            return self::$loader;
        }

        spl_autoload_register(array('ComposerAutoloaderInitdcaf1f150bc8a3b391a400f5763b2955', 'loadClassLoader'), true, true);
        self::$loader = $loader = new \Composer\Autoload\ClassLoader(\dirname(__DIR__));
        spl_autoload_unregister(array('ComposerAutoloaderInitdcaf1f150bc8a3b391a400f5763b2955', 'loadClassLoader'));

        require __DIR__ . '/autoload_static.php';
        call_user_func(\Composer\Autoload\ComposerStaticInitdcaf1f150bc8a3b391a400f5763b2955::getInitializer($loader));

        $loader->register(true);

        return $loader;
    }
}