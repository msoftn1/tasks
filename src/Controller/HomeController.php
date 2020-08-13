<?php

/**
 * Главный контроллер.
 */
class HomeController extends AbstractController
{
    /** Список полей по которым можно сортировать. */
    private array $allowSortFields = [
        'user_name',
        'email',
        'is_completed'
    ];

    /**
     * Основное действие.
     * @throws Exception
     */
    public function indexAction(): void
    {
        $sort = null;
        $sortType = null;
        if(isset($_GET['sort']) && in_array($_GET['sort'], $this->allowSortFields)) {
            $sort = $_GET['sort'];
            $sortType = (isset($_GET['sort_type']) && $_GET['sort_type'] == 'DESC') ? 'DESC' : 'ASC';
        }

        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = 3;

        $taskService = new TaskService();
        $userService = new UserService();

        $this->render("home/index",
            [
                'tasks' => $taskService->list($page, $limit, $sort, $sortType),
                'user' => Auth::check() ? $userService->getUserById(Auth::getUserId()) : null
            ]
        );
    }

    /**
     * Добавить задачу.
     */
    public function addTaskAction(): void
    {
        $userName = trim($_REQUEST['user_name']);
        $email = trim($_REQUEST['email']);
        $text = $_REQUEST['text'];

        $taskService = new TaskService();

        $this->responseJson(
            $taskService->add($userName, $email, $text)
        );
    }

    /**
     * Загрузить задачу по ID.
     */
    public function loadTaskAction(): void
    {
        if(!isset($_GET['id'])) {
            $this->responseJson(['status' => false]);
            return;
        }

        $id = (int)$_GET['id'];

        $taskService = new TaskService();

        $this->responseJson(
            $taskService->get($id)
        );
    }

    /**
     * Сохранить задачу.
     */
    public function saveTaskAction(): void
    {
        $id = (int)($_REQUEST['id']);
        $text = trim($_REQUEST['text']);

        $taskService = new TaskService();

        $this->responseJson(
            $taskService->save($id, $text)
        );
    }

    /**
     * Изменить статус.
     */
    public function changeStatusAction(): void
    {
        $id = (int)$_REQUEST['id'];
        $isCompleted = $_REQUEST['is_completed'] == 'true';

        $taskService = new TaskService();

        $this->responseJson(
            $taskService->changeStatus($id, $isCompleted)
        );
    }

    /**
     * Действие, когда страница "не найдена".
     *
     * @throws Exception
     */
    public function notFoundAction(): void
    {
        $this->render("home/notFound", [], 404);
    }
}
