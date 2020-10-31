<?php

namespace MyBlog\Services;


use MyBlog\Exceptions\DbException;

class Db
{
    private $pdo;
    private static $instance;

    private function __construct()
    {
        try {
            $dbOptions = (require __DIR__ . '/../../settings.php')['db'];

            $this->pdo = new \PDO(
                'mysql:host=' . $dbOptions['host'] . ';dbname=' . $dbOptions['dbname'],
                $dbOptions['user'],
                $dbOptions['password']
            );
            $this->pdo->exec('SET NAMES UTF-8');
        } catch (\PDOException $e) {
            throw new DbException('Ошибка при подключении к базе данных: ' . $e->getMessage());
        }

    }

    //Метод для обработки запроса
    public function query(string $sql, array $params = [], string $className = 'stdClass'): ?array
    {
        $sth = $this->pdo->prepare($sql);
        $result = $sth->execute($params);

        if(false === $result) {
            return null;
        }

        return $sth->fetchAll(\PDO::FETCH_CLASS, $className);
    }

    /*
        Обеспечивает не более одного соединения к БД
        Создание объекта класса Db: $db = Db::getInstance();
    */
    public static function getInstance(): self
    {
        if(self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    //получить id последней вставленной записи в базе (в рамках текущей сессии работы с БД)
    public function getLastInsertId(): int
    {
        return (int) $this->pdo->lastInsertId();
    }

}