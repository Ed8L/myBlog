<?php

namespace MyBlog\Controllers;

use MyBlog\Exceptions\InvalidArgumentException;
use MyBlog\Models\Comments\Comment;

class CommentsController extends AbstractController
{
    public function add($articleId)
    {
        if(!empty($_POST)) {
            try {
                $comment = Comment::createFromArray($_POST, $this->user->getId(), $articleId); // вставляет запись в таблицу
            } catch (InvalidArgumentException $e) {
                header('Location: /articles/' . $articleId);
            }
        }

        header('Location: /articles/' . $articleId . '#comment' . $comment->getId());
    }

    public function delete($commentId)
    {
        $comment = Comment::getById($commentId);
        $comment->delete();
        $prev_page = substr($_SERVER['HTTP_REFERER'],13);
        header('Location: '. $prev_page);
    }
}