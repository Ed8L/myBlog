<?php include __DIR__ . '/../header.php'; ?>
<div class="card">
    <div class="card-body">
        <h4>Добавить статью</h4>

        <?php if(!empty($error)): ?>
            <div style="color: red;"><?= $error ?></div>
        <?php endif; ?>

        <form action="/articles/add" method="POST">
            <div class="form-group">
                <label for="name">Название статьи</label>
                <input class="form-control" id="name" type="text" name="name" value="<?= $_POST['name'] ?? '' ?>">
            </div>
            <div class="form-group">
                <label for="text">Текст статьи</label>
                <textarea class="form-control" id="name" type="text" name="text" rows="10"><?= $_POST['text'] ?? '' ?></textarea>
            </div>
            <button type="submit" class="btn btn-outline-secondary">Добавить</button>
        </form>

    </div>
</div>
<?php include __DIR__ . '/../footer.php'; ?>