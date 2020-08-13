<?php


class UserRepository
{
    /**
     * Найти пользователя по логину и паролю.
     *
     * @param string $login
     * @param string $password
     * @return User|null
     */
    public function findByLoginAndPassword(string $login, string $password): ?User
    {
        $data = Db::getInstance()->selectWithParameters(
            'SELECT * FROM user WHERE login=:login AND password=:password',
            [
                'login' => $login,
                'password' => $password
            ]
        );

        $models = $this->hydrate($data);

        return count($models) > 0 ? $models[0] : null;
    }

    /**
     * Найти пользователя по ID.
     *
     * @param int $id
     * @return User|null
     */
    public function find(int $id): ?User
    {
        $data = Db::getInstance()->selectWithParameters(
            'SELECT * FROM user WHERE id=:user_id',
            [
                'user_id' => $id
            ]
        );

        $models = $this->hydrate($data);

        return count($models) > 0 ? $models[0] : null;
    }

    /**
     * Гидрировать объекты.
     *
     * @param array $data
     * @return array
     */
    private function hydrate(array $data): array
    {
        $userList = [];

        foreach($data as $item) {
            $user = new User();
            $user->id = (int) $item['id'];
            $user->email = $item['email'];
            $user->login = $item['login'];
            $user->password = $item['password'];
            $user->fio = $item['fio'];

            $userList[] = $user;
        }

        return $userList;
    }
}
