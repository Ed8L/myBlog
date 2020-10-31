<?php

namespace MyBlog\Models;

use MyBlog\Services\Db;

abstract class ActiveRecordEntity
{
    protected $id;

    //Получит id записи в таблице
    public function getId(): int
    {
        return $this->id;
    }

    //Вызывается неявно если имя свойства модели не соответствует имени поля таблицы
    // (createdAt(свойство модели) - created_at(поле таблицы))
    public function __set($name, $value)
    {
        $camelCaseName = $this->underscoreToCamelCase($name);
        $this->$camelCaseName = $value;
    }

    //Возвращает все записи с таблицы
    public static function findAll(): array
    {
        $db = Db::getInstance();
        return $db->query('SELECT * FROM `' . static::getTableName() . '` ORDER BY id DESC;', [], static::class);
    }

    //Получает запись с таблицы по id
    public static function getById(int $id): ?self
    {
        $db = Db::getInstance();
        $entities = $db->query(
            'SELECT * FROM `' . static::getTableName() . '` WHERE id=:id;',
            ['id' => $id],
            static::class
        );

        return $entities ? $entities[0] : null;
    }

    public function save(): void
    {
        $mappedProperties = $this->mapPropertiesToDbFormat();
        if ($this->id !== null) {
            $this->update($mappedProperties);
        } else {
            $this->insert($mappedProperties);
        }
    }

    //Редактирование записи
    private function update(array $mappedProperties)
    {
        $columns2params = [];
        $params2values = [];
        $index = 1;
        foreach($mappedProperties as $column => $value) {
            $param = ':param' . $index; // :param1
            $columns2params[] = $column . ' = ' . $param; // column1 = :param1
            $params2values[$param] = $value; // [:param1 => value1]
            $index++;
        }
        $sql = 'UPDATE ' . static::getTableName() . ' SET ' . implode(', ', $columns2params) . ' WHERE id = ' . $this->id;
        $db = Db::getInstance();
        $db->query($sql, $params2values, static::class);
    }

    //Вставка записи
    private function insert(array $mappedProperties)
    {
        $filteredProperties = array_filter($mappedProperties);

        $columns = [];
        $paramsNames = [];
        $params2values = [];
        foreach ($filteredProperties as $columnName => $value) {
            $columns[] = '`' . $columnName. '`';
            $paramName = ':' . $columnName;
            $paramsNames[] = $paramName;
            $params2values[$paramName] = $value;
        }

        $columnsViaSemicolon = implode(', ', $columns);
        $paramsNamesViaSemicolon = implode(', ', $paramsNames);

        $sql = 'INSERT INTO ' . static::getTableName() . ' (' . $columnsViaSemicolon . ') VALUES ('. $paramsNamesViaSemicolon .')';

        $db = Db::getInstance();
        $db->query($sql, $params2values, static::class);
        $this->id = $db->getLastInsertId();
    }

    //Удаление записи
    public function delete(): void
    {
        $db = Db::getInstance();
        $db->query(
            'DELETE FROM `' . static::getTableName() . '` WHERE id=:id',
            [':id' => $this->id],
            static::class
        );
        $this->id = null;
    }

    // Искать похожую запись
    public static function findOneByColumn(string $columnName, $value): ?self
    {
        $db = Db::getInstance();
        $result = $db->query(
            'SELECT * FROM `' . static::getTableName() . '` WHERE `' . $columnName . '` = :value LIMIT 1;',
            [':value' => $value],
            static::class
        );
        if($result === []) {
            return null;
        }
        return $result[0];
    }

    private function refresh(): void
    {
        $objectFromDb = static::getById($this->id);
        $reflector = new \ReflectionObject($objectFromDb);
        $properties = $reflector->getProperties();

        foreach ($properties as $property) {
            $property->setAccessible(true);
            $propertyName = $property->getName();
            $this->$propertyName = $property->getValue($objectFromDb);
        }
    }

    //Возвращает массив свойств с их значениями созданного объекта
    private function mapPropertiesToDbFormat(): array
    {
        $reflector = new \ReflectionObject($this);
        $properties = $reflector->getProperties();

        $mappedProperties = [];
        foreach ($properties as $property) {
            $propertyName = $property->getName();
            $propertyNameAsUnderscore = $this->camelCaseToUnderscore($propertyName);
            $mappedProperties[$propertyNameAsUnderscore] = $this->$propertyName;
        }

        return $mappedProperties;
    }

    //Переводит строку вида created_at => createdAt
    private function underscoreToCamelCase(string $source): string
    {
        return lcfirst(str_replace('_', '', ucwords($source, '_')));
    }

    //Переводит строку вида createdAt => created_at
    private function camelCaseToUnderscore(string $source): string
    {
        return strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $source));
    }

    abstract protected static function getTableName(): string;
}