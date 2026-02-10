<?php
use Auth\App\Config\Main;

/**
 * @var $login
 * @var $activation_link
 */
?>
<div>
    <div>Здравствуйте, <?= $login ?>!</div>
    <div>
        Для подтверждения электронной почты и активации вашего аккаунта на сайте
        <?= Main::getDomain(); ?>, пожалуйста, перейдите по <a href=<?= $activation_link ?>>этой ссылке</a>
    </div>
    <div>
        С уважением,
        Команда <?= Main::getTitle(); ?>
    </div>
</div>

