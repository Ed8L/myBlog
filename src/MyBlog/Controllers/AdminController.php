<?php

namespace MyBlog\Controllers;

use MyBlog\Exceptions\ForbiddenException;
use MyBlog\Models\Articles\Article;
use MyBlog\Models\Comments\Comment;

class AdminController extends AbstractController
{
    public function index()
    {
        if ($this->user === null || !$this->user->isAdmin()) {
            throw new ForbiddenException('Доступ запрещён');
        }

        $articles = Article::getShortArticles();
        $comments = Comment::findAll();

        $this->view->renderHtml('users/admin.php', ['articles' => $articles, 'comments' => $comments]);
    }
}