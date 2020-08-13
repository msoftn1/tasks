<?php

/**
 * Сервис работы с пользователями.
 */
class UserService
{
    /**
     * Авторизовация пользователя.
     *
     * @param $login
     * @param $password
     * @return array
     */
    public function auth($login, $password): array
    {
        if(empty($login) || empty($password)) {
            $error = 'не заполнены поля логин или пароль';
        }
        else {
            $repository = new UserRepository();
            $user = $repository->findByLoginAndPassword($login, sha1($password));

            if(!$user) {
                $error = 'пользователь с таким логином и паролем не найден.';
            }
        }

        $data = [];

        if($error !== null) {
            $data['status'] = false;
            $data['error'] = $error;
        }
        else {
            $data['status'] = true;

            Auth::authenticate($user->id);
        }

        return $data;
    }

    /**
     * Выход пользователя.
     */
    public function logout(): void
    {
        Auth::logout();
    }

    /**
     * Получить пользователя по ID.
     *
     * @param $userId
     * @return array|null
     */
    public function getUserById($userId): ?User
    {
        $repository = new UserRepository();
        $user = $repository->find($userId);

        if(!$user) {
            return null;
        }

        return $user;
    }
}
