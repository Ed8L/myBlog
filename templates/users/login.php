<?php include __DIR__ . '/../header.php'; ?>
<div class="card mt-5">
    <div class="card-body text-center">

        <h1>Вход</h1>

        <?php if (!empty($error)): ?>
            <div style="background-color: #343A40;padding: 5px;margin: 15px; color: red"><?= $error ?></div>
        <?php endif; ?>

        <div class="login">
            <form action="/users/login" method="post">
                <div class="form-group">
                    <label for="email">Почта</label>
                    <input class="form-control" id="email" type="email" name="email" value="<?= $_POST['email'] ?? '' ?>">
                </div>
                <div class="form-group">
                    <label for="password">Пароль</label>
                    <input type="password" id="password" class="form-control" name="password" value="<?= $_POST['password'] ?? '' ?>">
                </div>
                <button type="submit" class="btn btn-outline-secondary">Вход</button>
            </form>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../footer.php'; ?>