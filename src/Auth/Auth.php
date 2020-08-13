<?php

/**
 * Класс авторизации.
 */
class Auth
{
    /**
     * Авторизовать пользователя по ID.
     *
     * @param $userId
     */
    public static function authenticate($userId): void
    {
        $_SESSION['user_id'] = $userId;
    }

    /**
     * Выход пользователя.
     */
    public static function logout(): void
    {
        if(isset($_SESSION['user_id'])) {
            unset($_SESSION['user_id']);
        }
    }

    /**
     * Проверить авторизацию пользователя.
     *
     * @return bool
     */
    public static function check(): bool
    {
        return isset($_SESSION['user_id']);
    }

    /**
     * Получить user_id
     *
     * @return int|null
     */
    public static function getUserId(): ?int
    {
        if (self::check()) {
            return $_SESSION['user_id'];
        }

        return null;
    }
}
