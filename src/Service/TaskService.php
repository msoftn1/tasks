<?php

/**
 * Сервис работы с задачами.
 */
class TaskService
{
    /**
     * Список задач.
     *
     * @param int $page
     * @param int $limit
     * @param string|null $sort
     * @param string|null $sortType
     * @return array
     */
    public function list(int $page, int $limit, ?string $sort = null, ?string $sortType = null): array
    {
        $repository = new TaskRepository();
        $data = $repository->getList($page, $limit, $sort, $sortType);

        $cntTasks = $repository->cntAll();

        return [
            'count' => $cntTasks,
            'pages' => ceil($cntTasks/$limit),
            'data' => $data
        ];
    }

    /**
     * Добавить задачу.
     *
     * @param string $userName
     * @param string $email
     * @param string $text
     * @return array
     */
    public function add(string $userName, string $email, string $text): array
    {
        $repository = new TaskRepository();

        $error = null;
        if(empty($userName) || mb_strlen($userName, 'UTF-8') > 50) {
            $error = 'не указано имя пользователя или его длина превышает 50 символов';
        }
        else if(empty($email)) {
            $error = 'email не указан';
        }
        else if(!filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($email) > 100) {
            $error = 'email не валиден';
        }
        else if(empty($text) || mb_strlen($text, 'UTF-8') > 1000) {
            $error = 'не указан текст или его длина превышает 1000 символов';
        }

        $data = [];

        if($error !== null) {
            $data['status'] = false;
            $data['error'] = $error;
        }
        else {
            $data['status'] = true;

            $task = new Task();
            $task->userName = $userName;
            $task->email = $email;
            $task->text = $text;

            $repository->save($task);
        }

        return $data;
    }

    /**
     * Изменить статус задачи.
     *
     * @param int $id
     * @param bool $isCompleted
     * @return array
     */
    public function changeStatus(int $id, bool $isCompleted): array
    {
        $repository = new TaskRepository();

        $data = [];
        $data['status'] = true;

        $task = new Task();
        $task->id = $id;
        $task->isCompleted = $isCompleted;

        $repository->updateIsCompleted($task);

        return $data;
    }

    /**
     * Сохранить задачу.
     *
     * @param int $id
     * @param bool $isCompleted
     * @return array
     */
    public function save(int $id, string $text): array
    {
        $repository = new TaskRepository();

        if(empty($text) || mb_strlen($text, 'UTF-8') > 1000) {
            $error = 'не указан текст или его длина превышает 1000 символов';
        }

        $data = [];

        if($error !== null) {
            $data['status'] = false;
            $data['error'] = $error;
        }
        else {
            $data['status'] = true;

            $task = new Task();
            $task->id = $id;
            $task->text = $text;
            $task->isEdited = true;

            $repository->updateText($task);
        }

        return $data;
    }

    /**
     * Получить задачу по ID.
     *
     * @param int $id
     * @return array
     */
    public function get(int $id): array
    {
        $repository = new TaskRepository();
        $task = $repository->find($id);

        $rData = ['task' => null];

        if($task) {
            $rData['task'] = $task->toArray();
        }

        return $rData;
    }
}
