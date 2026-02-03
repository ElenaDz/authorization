<?php
require __DIR__.'/Auth/autoload.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>DriveMusic</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/4.0.0-rc.1/jquery.min.js" integrity="sha512-MXe5EK5gyK+fbhwQy/dukwz9fw71HZcsM4KsyDBDTvMyjymkiO0M5qqU0lF4vqLI4VnKf1+DIKf1GM6RFkO8PA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
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