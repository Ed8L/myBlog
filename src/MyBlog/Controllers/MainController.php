<?php
/*
    Контроллер главной страницы сайта
*/

namespace MyBlog\Controllers;

use MyBlog\Models\Articles\Article;
use MyBlog\Services\UsersAuthService;

class MainController extends AbstractController
{
    public function index()
    {
        $title = 'Главная страница';
        $articles = Article::findAll();
        $this->view->renderHtml('main/index.php', ['articles' => $articles, 'title' => $title]);
    }
}