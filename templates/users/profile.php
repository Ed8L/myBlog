<?php include __DIR__ . '/../header.php'?>
<div class="card">
    <div class="card-body text-center">
        <h4>Профиль</h4>
    </div>
</div>

<div class="card mt-2">
    <div class="card-body d-flex">
        <div class="avatar">
            <img src="img/avatar.jpg" alt="Avatar" width="150" height="150">
        </div>
        <div class="info">
            <h5><?= $user->getNickname() ?></h5>
            <p style="display: inline">Дата регистрации: </p><span><?= $user->getCreatedAt() ?></span>
        </div>
        <div class="options ml-auto">
            <a href="#">Загрузить аватар</a>
        </div>
    </div>
</div>
<?php include __DIR__ . '/../footer.php'?>