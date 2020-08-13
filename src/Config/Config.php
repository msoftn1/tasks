<?php

/**
 * Класс конфигурации.
 */
class Config
{
    /** @var string DSN базы данных. */
    const DB_DSN = 'mysql:host=127.0.0.1;dbname=tasks;charset=utf8';

    /** @var string Пользователь базы данных. */
    const DB_USER = 'root';

    /** @var string Пароль базы данных. */
    const DB_PASSWORD = 'root';

    /** @var string Веб директория приложения. */
    const WEB_PATH = '/';
}
