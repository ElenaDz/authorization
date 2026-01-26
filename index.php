<?php
require __DIR__.'/Auth/autoload.php';

// todo 0 установить любую программу для учета отработанного времени и начать ее использовать
//  например https://motivateclock.org/ через vpn сайт открывается ок
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>DriveMusic</title>
</head>
<body>
    <?php if ( ! \Auth\App\Service\Auth::isAuthorized()): ?>

    	<a href="<?= \Auth\App\Action\Logon::getUrl(['param_optional' => 'здесь должна быть форма ввода пароля']); ?>">
            Вход
        </a>

    <?php else: ?>

        <b><?= \Auth\App\Service\Auth::getUser()->getEmail(); ?></b>
        <a href="<?= \Auth\App\Action\Logout::getUrl(); ?>">
            Выход
        </a>

    <?php endif; ?>
</body>
</html>