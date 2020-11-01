<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css"
          integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    <link rel="stylesheet" href="/style.css">
    <title><?= $title ?? 'Мой блог' ?></title>
</head>
<body>

<nav class="navbar navbar-dark bg-dark navbar-expand-lg fixed-top">
    <a class="navbar-brand" href="/">Мой блог</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="hello ml-auto">
        <?php if (!empty($user)): ?>
            <span class="navbar-text">
                Привет, <?= $user->getNickname() ?> |
                <?php if($user->isAdmin()): ?>
                    <a href="/admin">Админка</a> |
                <?php endif ?>

                <?php if($user): ?>
                    <a href="/profile">Профиль</a>
                <?php endif?>
            </span>
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a href="/users/logout" class="nav-link">Выйти</a>
                </li>
            </ul>
        <?php else: ?>
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a href="/users/login">Войти</a> | <a href="/users/register">Зарегестрироваться</a>
                </li>
            </ul>
        <?php endif ?>
    </div>
</nav>
<div class="container mt-5">