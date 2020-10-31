<?php include __DIR__ .  '/../header.php'; ?>
<?php foreach ($articles as $article): ?>
<div class="card mt-3">
    <div class="card-body">
        <div class="title d-flex">
            <h3><a href="/articles/<?= $article->getId() ?>"><?= $article->getName() ?></a></h3>
            <span class="ml-auto">Дата: <?= $article->getCreatedAt() ?></span>
        </div>

        <p><?= $article->getText() ?></p>
        <?php if(!empty($user) && $user->isAdmin()): ?>
            <a href="/articles/<?= $article->getId() ?>/edit">Редактировать</a>
        <?php endif ?>
    </div>
</div>
<?php endforeach; ?>
<?php include __DIR__ .  '/../footer.php'; ?>