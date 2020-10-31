<?php include __DIR__ . '/../header.php'; ?>
    <div class="card mt-3">
        <div class="card-body">
            <div class="title d-flex">
                <h5 class="card-title"> <?= $article->getName() ?> </h5>
                <span class="ml-auto">Дата: <?= $article->getCreatedAt() ?></span>
            </div>

            <p class="card-text"> <?= $article->getText() ?> </p>
            <p>Автор: <?= $article->getAuthor() ?> </p>
        </div>
    </div>

    <div class="leave-comment mt-5">
        <h2>Комментарии</h2>

        <?php if ($authorized): ?>
            <form action="/articles/<?= $article->getId() ?>/add" method="POST">
                <div class="form-group">
                    <label for="text">Оставить комментарий</label>
                    <textarea name="text" type="text" class="form-control" id="text"></textarea>
                    <small class="form-text text-muted">Длина комментария должна быть не менее 3 символов</small>
                </div>
                <button type="submit" class="btn btn-primary">Отправить</button>
            </form>
        <?php else: ?>
            <h6>Чтобы оставить комментарий авторизуйтесь</h6>
        <?php endif ?>
    </div>

    <div class="card mt-4">
        <h5 style="padding: 8px 0 0 8px;"> <?= $message ? $message : '' ?> </h5>
        <?php foreach ($comments as $comment): ?>
            <div class="card-body">
                <a name="comment<?= $comment->getId() ?>"></a>
                <div class="title d-flex">
                    <p>Автор: <?= $comment->getAuthor() ?></p>
                    <span class="ml-auto">Дата: <?= $comment->getCreatedAt() ?></span>
                </div>

                <?= $comment->getText() ?>
                <?php if ( $authorized && ($user->getId() == $comment->getUserId() || $user->isAdmin()) ): ?>
                    <a href="/articles/<?= $comment->getArticleId() ?>/comment/<?= $comment->getId() ?>/delete">Удалить</a>
                <?php endif; ?>
            </div>
            <hr style="background-color:#fff;">
        <?php endforeach; ?>
    </div>
<?php include __DIR__ . '/../footer.php'; ?>