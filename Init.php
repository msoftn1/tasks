<?php

/**
 * Класс инициализации.
 */
class Init
{
    /** Объект класса. */
    private static ?Init $init = null;

    /**
     * Загрузка.
     *
     * @param string $src
     */
    public static function boot(string $src)
    {
        session_start();

        if (!self::$init) {
            $init = new static();
            $init->loadPhpFiles($src);
        }
    }

    /**
     * Загрузка класссов.
     *
     * @param string $dir
     */
    private function loadPhpFiles(string $dir)
    {
        $item = glob($dir);

        foreach ($item as $filename) {
            if (is_dir($filename)) {
                $this->loadPhpFiles($filename . '/' . "*");
            } elseif (is_file($filename)) {
                $extension = pathinfo($filename, PATHINFO_EXTENSION);
                if ($extension == 'php') {
                    require_once($filename);
                }
            }
        }
    }
}
