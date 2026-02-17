<?php
require __DIR__.'/Auth/autoload.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>DriveMusic</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js" integrity="sha512-bLT0Qm9VnAYZDflyKcBaQ2gg0hSYNQrJ8RilYldYQ1FxQYoCLtUjuuRuZo+fjqhx/qtq/1itJ0C2ejDxltZVFg==" crossorigin="anonymous"></script></head>
<body>
    <?php if ( ! \Auth\App\Service\Auth::isAuthorized()): ?>
    	<a href="<?= \Auth\App\Action\Logon::getUrl(); ?>">
            Вход
        </a><br>

    <?php else: ?>

        <b><?= \Auth\App\Service\Auth::getUser()->getEmail(); ?></b>
        <form method="post" action="<?= \Auth\App\Action\Logout::getUrl()?>">
            <button type="submit">Выход</button>
        </form>

    <?php endif; ?>
    
    <br>
    <a href="<?= \Auth\App\Action\TestBox::getUrl(); ?>">
        Тестовая площадка
    </a>
</body>
</html>