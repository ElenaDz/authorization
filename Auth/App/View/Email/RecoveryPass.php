<?php
use Auth\App\Config\Main;

/**
 * @var $login
 * @var $activation_link
 */
?>
<div>
    <div>Здравствуйте, <?= $login ?>!</div>
    <div>Вы или кто-то другой запросили новый пароль на сайте <?= Main::getDomain(); ?>.
        Для смены пароля, пожалуйста, перейдите по ссылке <a href=<?= $activation_link ?>>сменить пароль</a>
    </div>
    <div>
        С уважением,
        Команда <?= Main::getTitle(); ?>
    </div>
</div>

