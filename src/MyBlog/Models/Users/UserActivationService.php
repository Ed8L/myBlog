<?php

namespace MyBlog\Models\Users;

use MyBlog\Services\Db;

class UserActivationService
{
    private const TABLE_NAME = 'users_activation_codes';

    // Создает код активации аккаунта
    public static function createActivationCode(User $user): string
    {
        $code = bin2hex(random_bytes(16));

        $db = Db::getInstance();
        $db->query(
            'INSERT INTO ' . self::TABLE_NAME . ' (user_id, code) VALUES (:user_id, :code)',
            [
                'user_id' => $user->getId(),
                'code' => $code
            ]
        );

        return $code;
    }

    // Проверяет код активации
    public static function checkActivationCode(User $user, string $code): bool
    {
        $db = Db::getInstance();
        $result = $db->query(
            'SELECT * FROM ' . self::TABLE_NAME . ' WHERE user_id = :user_id AND code = :code',
            [
                'user_id' => $user->getId(),
                'code' => $code
            ]
        );
        return !empty($result);
    }

    public static function deleteActivationCode($userId): void
    {
        $db = Db::getInstance();
        $db->query(
            'DELETE FROM ' . self::TABLE_NAME . ' WHERE user_id = :user_id',
            [
                'user_id' => $userId
            ]
        );
    }
}