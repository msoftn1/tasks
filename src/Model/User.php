<?php

/**
 * Сущность пользователей.
 */
class User
{
    /** Идентификатор. */
    public int $id;

    /** Email. */
    public string $email;

    /** Логин. */
    public string $login;

    /** Пароль. */
    public string $password;

    /** ФИО. */
    public string $fio;
}
