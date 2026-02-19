<?php
require __DIR__.'/Auth/autoload.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>DriveMusic</title>
</head>
<body>
    <?php if ( ! \Auth\App\Service\Auth::isAuthorized()): ?>
    	<a href="<?= \Auth\App\Action\Logon::getUrl(); ?>">
            Вход
        </a><br>

    <?php else: ?>

        <b><?= \Auth\App\Service\Auth::getUser()->getLogin(); ?></b>
        ( <?= \Auth\App\Service\Auth::getUser()->getEmail(); ?> )
        <form method="post" action="<?= \Auth\App\Action\Logout::getUrl()?>">
            <button type="submit">Выход</button>
        </form>

    <?php endif; ?>
    
    <br>
    <a href="<?= \Auth\App\Action\TestBox::getUrl(); ?>">
        Тестовая площадка
    </a>

    <br>
    <h2>Cron</h2>
    <ul>
        <li>
            <a href="/Auth/do_cron.php?job=<?= \Auth\App\Action\DeleteNotActivatedUsers::class ?>">
                Удалить не активированных пользователей
            </a>
        </li>
    </ul>

    <script
            src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"
            integrity="sha512-bLT0Qm9VnAYZDflyKcBaQ2gg0hSYNQrJ8RilYldYQ1FxQYoCLtUjuuRuZo+fjqhx/qtq/1itJ0C2ejDxltZVFg=="
            crossorigin="anonymous"
    ></script>
</body>
</html>