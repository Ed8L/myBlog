<?php
/*
    Контроллер одной статьи
*/

namespace MyBlog\Controllers;

use MyBlog\Exceptions\ForbiddenException;
use MyBlog\Exceptions\InvalidArgumentException;
use MyBlog\Exceptions\NotFoundException;
use MyBlog\Exceptions\UnauthorizedException;
use MyBlog\Models\Articles\Article;
use MyBlog\Models\Comments\Comment;

class ArticlesController extends AbstractController
{
    public function view(int $articleId): void
    {
        $article = Article::getById($articleId);

        $comments = Comment::getComments($articleId);

        if($article === null) {
            throw new NotFoundException();
        }

        if($this->user === null) {
            $notAuthorized = true;
        } else {
            $notAuthorized = false;
        }

        $title = $article->getName();

        $this->view->renderHtml('articles/view.php', ['article' => $article, 'title' => $title, 'comments' => $comments, 'notAuthorized' => $notAuthorized]);
    }

    public function edit(int $articleId): void
    {
        $article = Article::getById($articleId);

        if($article === null) {
            throw new NotFoundException();
        }

        if ($this->user === null || !$this->user->isAdmin()) {
            throw new ForbiddenException('Доступ запрещён');
        }

        if(!empty($_POST)) {
            try {
                $article->updateFromArray($_POST);
            } catch (InvalidArgumentException $e) {
                $this->view->renderHtml('articles/edit.php', ['error' => $e->getMessage(), 'article' => $article]);
                return;
            }

            header('Location: /articles/' . $article->getId(), true, 302);
            exit();
        }

        $title = 'Редактировать статью';

        $this->view->renderHtml('articles/edit.php', ['article' => $article, 'title' => $title]);
    }

    public function add(): void
    {
        if ($this->user === null || !$this->user->isAdmin()) {
            throw new ForbiddenException('Доступ запрещён');
        }

        if(!empty($_POST)) {
            try {
                $article = Article::createFromArray($_POST, $this->user);
            } catch (InvalidArgumentException $e) {
                $this->view->renderHtml('articles/add.php', ['error' => $e->getMessage()]);
                return;
            }

            header('Location: /articles/' . $article->getId(), true, 302);
            exit();
        }

        $title = 'Добавить статью';

        $this->view->renderHtml('articles/add.php', ['title' => $title]);
    }

    public function delete($articleId): void
    {
        $article = Article::getById($articleId);
        if($article === null) {
            throw new NotFoundException();
        }
        $article->delete();
        header('Location: /admin');
    }
}