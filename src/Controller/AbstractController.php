<?php

/**
 * Абстрактный класс контроллер.
 */
abstract class AbstractController
{
    /**
     * Редирект.
     *
     * @param $url
     */
    protected function redirectTo($url): void
    {
        header('Location: ' . $url);
    }

    /**
     * Рендеринг шаблона.
     *
     * @param string $name
     * @param array $data
     * @param int $status
     * @throws Exception
     */
    protected function render(string $name, array $data = [], $status = 200): void
    {
        $view = new View();
        $view->data = $data;

        http_response_code($status);
        header('Content-Type:text/html');

        echo $view->render('../templates/' . $name . ".php");
    }

    /**
     * JSON ответ от сервера.
     *
     * @param array $data
     * @param int $status
     */
    protected function responseJson(array $data = [], $status = 200): void
    {
        http_response_code($status);
        header('Content-Type:application/json');

        echo json_encode($data);
    }
}
