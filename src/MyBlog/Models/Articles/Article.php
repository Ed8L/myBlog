<?php

namespace MyBlog\Models\Articles;

use MyBlog\Exceptions\InvalidArgumentException;
use MyBlog\Models\ActiveRecordEntity;
use MyBlog\Models\Users\User;
use MyBlog\Services\Db;

class Article extends ActiveRecordEntity
{
    protected $name;
    protected $text;
    protected $authorId;
    protected $createdAt;

    public static function createFromArray(array $fields, User $author): Article
    {
        if (empty($fields['name'])) {
            throw new InvalidArgumentException('Не передано название статьи');
        }

        if (empty($fields['text'])) {
            throw new InvalidArgumentException('Не передан текст статьи');
        }

        $article = new Article();

        $article->setAuthor($author);
        $article->setName($fields['name']);
        $article->setText($fields['text']);

        $article->save();

        return $article;
    }

    public function updateFromArray(array $fields): Article
    {
        if(empty($fields['name'])) {
            throw new InvalidArgumentException('Не передано название статьи');
        }

        if(empty($fields['text'])) {
            throw new InvalidArgumentException('Не передан текст статьи');
        }

        $this->setName($fields['name']);
        $this->setText($fields['text']);

        $this->save();

        return $this;
    }

    public static function getShortArticles(): array
    {
        $db = Db::getInstance();
        $sql = 'SELECT id, name, CONCAT(LEFT(text,100), "...") as text, author_id, created_at FROM ' . self::getTableName() . ';';
        return $db->query($sql, [], Article::class);
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getText(): string
    {
        return $this->text;
    }

    public function setText(string $text): void
    {
        $this->text = $text;
    }

    public function getAuthor()
    {
        $user = User::getById($this->authorId);
        return $user? $user->getNickname(): 'Аккаунт удален';
    }

    public function setAuthor(User $author): void
    {
        $this->authorId = $author->getId();
    }

    public function getCreatedAt()
    {
        $date = date_create($this->createdAt);
        return date_format($date, 'd-m-Y | H:s');
    }

    protected static function getTableName(): string
    {
        return 'articles';
    }
}