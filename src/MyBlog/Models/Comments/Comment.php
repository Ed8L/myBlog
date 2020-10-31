<?php

namespace MyBlog\Models\Comments;

use MyBlog\Exceptions\InvalidArgumentException;
use MyBlog\Models\ActiveRecordEntity;
use MyBlog\Models\Users\User;
use MyBlog\Models\Articles\Article;
use MyBlog\Services\Db;

class Comment extends ActiveRecordEntity
{
    protected $userId;
    protected $articleId;
    protected $text;
    protected $createdAt;

    public static function createFromArray(array $fields, $userId, $articleId)
    {
        if (empty($fields['text'])) {
            throw new InvalidArgumentException('Не передан текст комментария');
        }

        if (mb_strlen($fields['text']) < 3) {
            throw new InvalidArgumentException('Длина комментария должна быть не менее 3 символов');
        }

        $comment = new Comment();

        $comment->setText($fields['text']);
        $comment->setArticleId($articleId);
        $comment->setUserId($userId);

        $comment->save();

        return $comment;
    }

    public function updateComment(array $fields)
    {
        if(empty($fields['text'])) {
            throw new InvalidArgumentException('Не передан текст комментария');
        }

        if (mb_strlen($fields['text']) < 3) {
            throw new InvalidArgumentException('Длина комментария должна быть не менее 3 символов');
        }

        $this->setText($fields['text']);
        $this->save();
        return $this;
    }

    public static function getComments($articleId)
    {
        $db = Db::getInstance();
        $sql = 'SELECT * FROM ' . self::getTableName() . ' WHERE article_id=:article_id';
        return $db->query($sql,['article_id' => $articleId], Comment::class);
    }

    public function setText($text): void
    {
        $this->text = $text;
    }

    public function getText(): string
    {
        return $this->text;
    }

    public function setUserId($userId): void
    {
        $this->userId = $userId;
    }

    public function getAuthor()
    {
        $user = User::getById($this->userId);
        return $user? $user->getNickname(): 'Аккаунт удален';
    }

    public function setArticleId($articleId): void
    {
        $this->articleId = $articleId;
    }

    public function getArticleId()
    {
        return $this->articleId;
    }

    public function getCreatedAt()
    {
        $date = date_create($this->createdAt);
        return date_format($date, 'd-m-Y | H:i:s');
    }

    protected static function getTableName(): string
    {
        return 'comments';
    }
}