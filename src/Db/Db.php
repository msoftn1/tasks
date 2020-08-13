<?php

/**
 * Singleton класс для работы с базой данных.
 */
class Db
{
    /** Объект. */
    private static ?Db $db = null;

    /** Объект PDO. */
    private PDO $dbh;

    /**
     * Создать объект класса.
     *
     * @return Db
     */
    public static function getInstance(): Db
    {
        if(!self::$db) {
            self::$db = new static();
            self::$db->connect();
        }

        return self::$db;
    }

    /**
     * Подключиться к базе данных.
     */
    private function connect(): void
    {
        $this->dbh = new PDO(Config::DB_DSN, Config::DB_USER, Config::DB_PASSWORD);
        $this->dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    /**
     * Выполнить SELECT запрос.
     *
     * @param string $sql
     * @return array
     */
    public function select(string $sql): array
    {
        $list = [];

        foreach ($this->dbh->query($sql) as $row) {
            $list[] = $row;
        }

        return $list;
    }

    /**
     * Выполнить SELECT запрос с параметрами.
     *
     * @param string $sql
     * @param array $parameters
     * @return array
     */
    public function selectWithParameters(string $sql, array $parameters): array
    {
        $list = [];

        $sth = $this->dbh->prepare($sql);
        $sth->execute($parameters);

        while($row = $sth->fetch(PDO::FETCH_ASSOC)){
            $list[] = $row;
        }

        return $list;
    }

    /**
     * Выполнить INSERT запрос с параметрами.
     *
     * @param string $sql
     * @param array $parameters
     */
    public function insertWithParameters(string $sql, array $parameters): void
    {
        $sth = $this->dbh->prepare($sql);
        $sth->execute($parameters);
    }

    /**
     * Выполнить UPDATE запрос с параметрами.
     *
     * @param string $sql
     * @param array $parameters
     */
    public function updateWithParameters(string $sql, array $parameters): void
    {
        $sth = $this->dbh->prepare($sql);
        $sth->execute($parameters);
    }
}
