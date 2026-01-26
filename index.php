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
        </a>

    <?php else: ?>

        <b><?= \Auth\App\Service\Auth::getUser()->getEmail(); ?></b>
        <a href="<?= \Auth\App\Action\Logout::getUrl(); ?>">
            Выход
        </a>

    <?php endif; ?>
</body>
</html>