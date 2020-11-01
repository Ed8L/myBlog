<?php

namespace MyBlog\Controllers;

use MyBlog\Exceptions\InvalidArgumentException;
use MyBlog\Exceptions\NotFoundException;
use MyBlog\Exceptions\UnauthorizedException;
use MyBlog\Models\Users\User;
use MyBlog\Models\Users\UserActivationService;
use MyBlog\Services\EmailSender;
use MyBlog\Services\UsersAuthService;

class UsersController extends AbstractController
{
    public function signUp()
    {
        if (!empty($_POST)) {
            try {
                $user = User::signUp($_POST);
            } catch (InvalidArgumentException $e) {
                $this->view->renderHtml('users/signUp.php', ['error' => $e->getMessage()]);
                return;
            }

            if ($user instanceof User) {
                $code = UserActivationService::createActivationCode($user);

                EmailSender::send($user, 'Активация', 'userActivation.php', ['user_id' => $user->getId(), 'code' => $code]);

                $this->view->renderHtml('users/signupSuccessful.php');
                return;
            }
        }

        $this->view->renderHtml('users/signUp.php');
    }

    public function activate(int $userId, string $activationCode)
    {
        $user = User::getById($userId);

        if ($user === null) {
            throw new NotFoundException();
        }

        $isCodeValid = UserActivationService::checkActivationCode($user, $activationCode);
        if ($isCodeValid) {
            $user->activate();
            UserActivationService::deleteActivationCode($userId);
            echo 'OK';
        } else {
            throw new NotFoundException();
        }
    }

    public function profile()
    {
        if($this->user === null) {
            throw new UnauthorizedException();
        }

        if(!empty($_FILES['avatar'])) {
            $this->uploadAvatar($_FILES['avatar']);
        }

        $this->view->renderHtml('users/profile.php');
    }

    private function uploadAvatar(array $file)
    {
        $result = false;
        if ($file['error'] !== UPLOAD_ERR_OK) {
            return $result = 'Ошибка при загрузке файла.';
        } elseif($file['error'] === UPLOAD_ERR_NO_FILE) {
            return $result = 'Файл не выбран!';
        }

        $srcFileName = $file['name'];
        $extension = pathinfo($srcFileName, PATHINFO_EXTENSION);
        $userName = $this->user->getNickname();

        $avatarPath = __DIR__ . '/../../../www/img/' . $userName . '.jpg';

        if ( move_uploaded_file($file['tmp_name'], $avatarPath) ) {
            $result = $avatarPath;
        }

        return $result;
    }

    public function login()
    {
        if (!empty($_POST)) {
            try {
                $user = User::login($_POST);
                UsersAuthService::createToken($user);
                header('Location: /');
                exit();
            } catch (InvalidArgumentException $e) {
                $this->view->renderHtml('users/login.php', ['error' => $e->getMessage()]);
                return;
            }
        }
        $this->view->renderHtml('users/login.php');
    }

    public function logout()
    {
        if (!empty($_COOKIE)) {
            setcookie('token', '', -1, '/', '', false, true);
            header('Location: /');
            exit();
        }
    }
}