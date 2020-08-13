<?php

/**
 * Основной класс приложения.
 */
class TasksApplication
{
    /** Защищенные роуты. */
    private array $protectedRoutes = [
        '/save-task',
        '/load-task',
        '/change-status'
    ];

    /** Роуты для которых пользователь не должен быть авторизован. */
    private array $notProtectedRoutes = [
        '/admin',
        '/admin/auth'
    ];

    /**
     * Запуск приложения.
     *
     * @throws AccessException
     */
    public function start(): void
    {
        $this->routing();
    }

    /**
     * Роутинг.
     *
     * @throws AccessException
     */
    private function routing(): void
    {
        $request = $this->getPath();

        if ((in_array($request, $this->protectedRoutes) && !Auth::check())
            || (in_array($request, $this->notProtectedRoutes) && Auth::check())) {
            throw new AccessException("Доступ запрещен");
        }

        if ($request == '/') {
            $controller = new HomeController();
            $controller->indexAction();
        }
        else if ($request == '/add-task') {
            $controller = new HomeController();
            $controller->addTaskAction();
        }
        else if ($request == '/change-status') {
            $controller = new HomeController();
            $controller->changeStatusAction();
        }
        else if ($request == '/load-task') {
            $controller = new HomeController();
            $controller->loadTaskAction();
        }
        else if ($request == '/save-task') {
            $controller = new HomeController();
            $controller->saveTaskAction();
        } else if ($request == '/admin') {
            $controller = new AdminController();
            $controller->indexAction();
        }  else if ($request == '/admin/auth') {
            $controller = new AdminController();
            $controller->authAction();
        } else if ($request == '/admin/logout') {
            $controller = new AdminController();
            $controller->logoutAction();
        } else {
            $controller = new HomeController();
            $controller->notFoundAction();
        }
    }

    /**
     * Получить роут.
     *
     * @return string
     */
    private function getPath(): string
    {
        $data = explode('?', $_SERVER['REQUEST_URI']);
        return $data[0];
    }
}
