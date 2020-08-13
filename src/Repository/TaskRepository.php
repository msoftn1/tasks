<?php

/**
 * Репозиторий задач.
 */
class TaskRepository
{
    /**
     * Получить список задач с пагинацией и сортировкой.
     *
     * @param int $page
     * @param int $limit
     * @param string|null $sort
     * @param string|null $sortType
     * @return array
     */
    public function getList(int $page, int $limit, ?string $sort = null, ?string $sortType = null): array
    {
        if(!$sort) {
            $sort = 'id';
            $sortType = 'DESC';
        }

        $query = 'SELECT * FROM task';
        $query .= sprintf(' ORDER BY %s %s', $sort, $sortType);

        $offset = ($page - 1) * $limit;
        $query .= sprintf(' LIMIT %s OFFSET %s', $limit, $offset);

        $data = Db::getInstance()->selectWithParameters($query, []);

        return $this->hydrate($data);
    }

    /**
     * Получить общее количество задач.
     *
     * @return int
     */
    public function cntAll(): int
    {
        $data = Db::getInstance()->selectWithParameters('SELECT COUNT(*) as cnt FROM task', []);

        return $data[0]['cnt'];
    }

    /**
     * Сохранить задачу.
     *
     * @param Task $task
     */
    public function save(Task $task): void
    {
        DB::getInstance()->insertWithParameters(
            "INSERT INTO task (user_name,email,text) VALUES (:user_name,:email,:text)",
            [
                'user_name' => $task->userName,
                'email' => $task->email,
                'text' => $task->text
            ]
        );
    }

    /**
     * Обновить поле статус.
     *
     * @param Task $task
     */
    public function updateIsCompleted(Task $task): void
    {
        DB::getInstance()->updateWithParameters(
            "UPDATE task SET is_completed=:is_completed WHERE id=:task_id",
            [
                'task_id' => $task->id,
                'is_completed' => $task->isCompleted ? 1 : 0
            ]
        );
    }

    /**
     * Обновить поле текст.
     *
     * @param Task $task
     */
    public function updateText(Task $task): void
    {
        DB::getInstance()->updateWithParameters(
            "UPDATE task SET text=:text, is_edited=:is_edited WHERE id=:task_id",
            [
                'task_id' => $task->id,
                'text' => $task->text,
                'is_edited' => $task->isEdited ? 1 : 0,
            ]
        );
    }

    /**
     * Найти задачу.
     *
     * @param int $id
     * @return Task|null
     */
    public function find(int $id): ?Task
    {
        $data = Db::getInstance()->selectWithParameters('SELECT * FROM task WHERE id=:task_id', [
            'task_id' => $id
        ]);

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
        $taskList = [];

        foreach($data as $item) {
            $task = new Task();
            $task->id = (int) $item['id'];
            $task->userName = $item['user_name'];
            $task->email = $item['email'];
            $task->text = $item['text'];
            $task->isCompleted = (bool) $item['is_completed'];
            $task->isEdited = (bool) $item['is_edited'];

            $taskList[] = $task;
        }

        return $taskList;
    }
}
