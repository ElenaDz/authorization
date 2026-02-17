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
        <!-- todo замени ссылку на кнопку формы, так как может быть такое что бот по ссылки пойдет и активирует -->
        <?= Main::getDomain(); ?>, пожалуйста, перейдите по <a href=<?= $activation_link ?>>этой ссылке</a>
    </div>
    <div>
        С уважением,
        Команда <?= Main::getTitle(); ?>
    </div>
</div>

