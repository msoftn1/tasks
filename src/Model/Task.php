<?php

/**
 * Сущность задач.
 */
class Task
{
    /** Идентификатор. */
    public int $id;

    /** Имя пользователя. */
    public string $userName;

    /** Email. */
    public string $email;

    /** Текст. */
    public string $text;

    /** Завершено. */
    public bool $isCompleted;

    /** Отредактировано. */
    public bool $isEdited;

    /**
     * Представление в виде массива.
     *
     * @return array
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'user_name' => $this->userName,
            'email' => $this->email,
            'text' => $this->text,
            'is_completed' => $this->isCompleted,
            'is_edited' => $this->isEdited
        ];
    }
}
