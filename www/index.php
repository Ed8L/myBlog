<?php
/*
     Фронт-контроллер
 */
try {


    spl_autoload_register(function (string $className) {
        require_once __DIR__ . '/../src/' . $className . '.php';
    });

    $route = $_GET['route'] ?? '';
    $routes = require __DIR__ . '/../src/routes.php';

    $isRouteFound = false;
    foreach ($routes as $pattern => $controllerAndAction) {
        preg_match($pattern, $route, $matches);
        if (!empty($matches)) {
            $isRouteFound = true;
            break;
        }
    }

    if (!$isRouteFound) {
        throw new \MyBlog\Exceptions\NotFoundException();
    }

    unset($matches[0]); //Удаляем полное совпадение

    $controllerName = $controllerAndAction[0]; //Контроллер
    $actionName = $controllerAndAction[1]; //Имя метода в контроллере(экшн)

    $controller = new $controllerName();
    $controller->$actionName(...$matches); //Передаем элемент массива в качестве аргумента
} catch (\MyBlog\Exceptions\DbException $e) {
    $view = new \MyBlog\View\View(__DIR__ . '/../templates/errors');
    $view->renderHtml('500.php', ['error' => $e->getMessage()], 500);
} catch (\MyBlog\Exceptions\NotFoundException $e) {
    $view = new \MyBlog\View\View(__DIR__ . '/../templates/errors');
    $view->renderHtml('404.php', ['error' => $e->getMessage()], 404);
} catch (\MyBlog\Exceptions\UnauthorizedException $e) {
    $view = new \MyBlog\View\View(__DIR__ . '/../templates/errors');
    $view->renderHtml('401.php', ['error' => $e->getMessage()], 401);
} catch (\MyBlog\Exceptions\ForbiddenException $e) {
    $view = new \MyBlog\View\View(__DIR__ . '/../templates/errors');
    $view->renderHtml('403.php', ['error' => $e->getMessage()], 403);
}