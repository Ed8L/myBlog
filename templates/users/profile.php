<?php include __DIR__ . '/../header.php' ?>
    <div class="card">
        <div class="card-body text-center">
            <h4>Профиль</h4>
        </div>
    </div>

    <div class="card mt-2">
        <div class="card-body d-flex">
            <div class="avatar">
                <img src="/img/<?= $user->getNickname() ?>.jpg" alt="Аватар" width="110" height="110">
            </div>
            <div class="info">
                <h5><?= $user->getNickname() ?></h5>
                <p style="display: inline">Дата регистрации: </p><span><?= $user->getCreatedAt() ?></span>
            </div>

            <div class="options ml-auto">
                <form action="/profile" method="POST" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="avatar">Загрузить аватар</label>
                        <input type="file" class="form-control-file" id="avatar" name="avatar">
                        <input type="submit">
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php include __DIR__ . '/../footer.php' ?>