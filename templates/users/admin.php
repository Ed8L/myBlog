<?php include __DIR__ . '/../header.php'; ?>
    <div class="card">
        <div class="card-body">
            <a href="/articles/add" class="btn btn-primary btn-sm active" role="button" aria-pressed="true">Добавить
                статью</a>
        </div>
    </div>
    <div class="row admin-panel mt-4">
        <div class="col articles">
            <h4>Статьи</h4>
            <?php foreach ($articles as $article): ?>
                <div class="card mt-2">
                    <div class="card-body">
                        <div class="title d-flex">
                            <h5><a href="/articles/<?= $article->getId() ?>"><?= $article->getName() ?></a></h5>
                            <span class="ml-auto">Дата: <?= $article->getCreatedAt() ?></span>
                        </div>
                        <p><?= $article->getText() ?></p>
                        <a href="/articles/<?= $article->getId() ?>/edit">Редактировать</a>
                        |
                        <a href="/articles/<?= $article->getId() ?>/delete">Удалить</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="col comments">
            <h4>Последние комментарии</h4>
            <?php foreach ($comments as $comment): ?>
                <div class="card mt-2">
                    <a name="<?= $comment->getId() ?>"></a>
                    <div class="card-body">
                        <div class="title d-flex">
                            <p>Автор: <?= $comment->getAuthor() ?></p>
                            <span class="ml-auto">Дата: <?= $comment->getCreatedAt() ?></span>
                        </div>
                        <p> <?= $comment->getText() ?> </p>

                        <a href="articles/<?= $comment->getArticleId() ?>/comment/<?= $comment->getId() ?>/delete">Удалить</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
<?php include __DIR__ . '/../footer.php'; ?>