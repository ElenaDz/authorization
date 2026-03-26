<?php
/**
 * @var $email
 */

use Auth\App\Action\Logon;

?>

<div>
    <h1>Успешно</h1>
    <div>
        <span>
            Ваш пароль был успешно изменен. Вы можете
            <a href="<?= Logon::getUrl([Logon::POST_NAME_EMAIL => $email]); ?>">
                войти на сайт
            </a>, используя ваш новый пароль.
        </span>
    </div>
</div>