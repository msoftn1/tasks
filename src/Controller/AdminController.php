<?php

/**
 * Контроллер администратора.
 */
class AdminController extends AbstractController
{
    /**
     * Главная страница администратора.
     */
    public function indexAction(): void
    {
        $this->render("admin/index");
    }

    /**
     * Авторизация.
     */
    public function authAction(): void
    {
        $login = trim($_REQUEST['login']);
        $password = $_REQUEST['password'];

        $userService = new UserService();

        $this->responseJson(
            $userService->auth($login, $password)
        );
    }

    /**
     * Выход.
     */
    public function logoutAction(): void
    {
        $userService = new UserService();
        $userService->logout();

        $this->redirectTo('/');
    }
}
