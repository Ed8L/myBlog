<?php

namespace MyBlog\Services;

use MyBlog\Models\Users\User;

class EmailSender
{
    // Отправляет письмо с кодом активации на почту
    public static function send(User $receiver, string $subject, string $templateName, array $templateVars = []): void
    {
        extract($templateVars);

        ob_start();
        require __DIR__ . '/../../../templates/mail/' . $templateName;
        $body = ob_get_contents();
        ob_end_clean();

        mail($receiver->getEmail(), $subject, $body, 'Content-Type: text/html; charset=UTF-8');
    }
}