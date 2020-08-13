<?php

/**
 * Шаблонизатор.
 */
class View
{
    /** Данные для шаблона. */
    public $data = [];

    /**
     * Рендеринт шаблона
     *
     * @param string|null $template
     */
    public function render(string $template): void
    {
        if (!is_file($template)) {
            throw new RuntimeException('Template not found: ' . $template);
        }

        $this->loadFunctions();

        $result = function($file, array $data = array()) {
            ob_start();
            extract($data, EXTR_SKIP);
            try {
                include $file;
            } catch (\Exception $e) {
                ob_end_clean();
                throw $e;
            }
            return ob_get_clean();
        };

        echo $result($template, $this->data);
    }

    /**
     * Инициализировать функции.
     */
    private function loadFunctions(): void
    {
        $this->data['escape'] = $this->getEscapeFunction();
        $this->data['sortUrl'] = $this->getSortUrlFunction();
        $this->data['pagerUrl'] = $this->getPagerUrlFunction();
        $this->data['url'] = $this->getUrlFunction();
    }

    /**
     * Экранирование.
     *
     * @return Closure
     */
    private function getEscapeFunction(): Closure
    {
        return function(string $value)
        {
            return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
        };
    }

    /**
     * Построение запроса сортировки.
     *
     * @return Closure
     */
    private function getSortUrlFunction(): Closure
    {
        return function(string $value)
        {
            $sortType = (isset($_GET['sort_type']) && $_GET['sort_type'] == 'DESC') ? 'ASC' : 'DESC';

            $url = sprintf(Config::WEB_PATH . '?sort=%s&sort_type=%s', $value, $sortType);

            if(isset($_GET['page'])) {
                $url .= sprintf('&page=%s', $_GET['page']);
            }

            return $url;
        };
    }

    /**
     * Построение запроса пагинации.
     *
     * @return Closure
     */
    private function getPagerUrlFunction(): Closure
    {
        return function(int $page)
        {
            $sort = isset($_GET['sort']) ? $_GET['sort'] : null;
            $sortType = isset($_GET['sort_type']) ? $_GET['sort_type'] : null;

            $url = sprintf(Config::WEB_PATH . '?page=%s', $page);

            if($sort && $sortType) {
                $url .= '&sort=%s&sort_type=%s';
            }

            return sprintf($url, $sort, $sortType);
        };
    }

    /**
     * Генерация url.
     *
     * @return Closure
     */
    private function getUrlFunction(): Closure
    {
        return function(string $uri)
        {
            return Config::WEB_PATH . $uri;
        };
    }
}
