<?php

return [
    //Статьи
    '~^$~' => [\MyBlog\Controllers\MainController::class, 'index'],
    '~^articles/(\d+)$~' => [\MyBlog\Controllers\ArticlesController::class, 'view'],
    '~^articles/(\d+)/edit$~' => [\MyBlog\Controllers\ArticlesController::class, 'edit'],
    '~^articles/add$~' => [\MyBlog\Controllers\ArticlesController::class, 'add'],
    '~^articles/(\d+)/delete$~' => [\MyBlog\Controllers\ArticlesController::class, 'delete'],

    //Комментарии
    '~^articles/(\d+)#comment(\d+)$~' => [\MyBlog\Controllers\CommentsController::class, 'add'], // Якорь
    '~^articles/(\d+)/add~' => [\MyBlog\Controllers\CommentsController::class, 'add'],
    '~^articles/\d+/comment/(\d+)/edit$~' => [\MyBlog\Controllers\CommentsController::class, 'edit'],
    '~^articles/\d+/comment/(\d+)/delete$~' => [\MyBlog\Controllers\CommentsController::class, 'delete'],

    //Пользователи
    '~^users/register$~' => [\MyBlog\Controllers\UsersController::class, 'signUp'],
    '~^users/(\d+)/activate/(.+)$~' => [\MyBlog\Controllers\UsersController::class, 'activate'],
    '~^users/login$~' => [\MyBlog\Controllers\UsersController::class, 'login'],
    '~^users/logout$~' => [\MyBlog\Controllers\UsersController::class, 'logout'],
    '~^profile$~' => [\MyBlog\Controllers\UsersController::class, 'profile'],

    //Админ панель
    '~^admin$~' => [\MyBlog\Controllers\AdminController::class, 'index'],
    '~^admin/#comment(\d+)$~' => [\MyBlog\Controllers\CommentsController::class, 'edit']
];