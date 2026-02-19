<?php
use Auth\App\Action\RecoveryPass;

/**
 * @var $email_error
 */

?>

<div class="title_form">
    <h1>Восстановить пароль</h1>
    <a class="exit" href="/"></a>
</div>

<div>
    <form method="post"  action="<?=  RecoveryPass::getUrl(); ?>">
        <span>
            Введите адрес электронной почты, указанный при регистрации вашего аккаунта. Мы направим ссылку для сброса пароля.
        </span>

        <div class="item">
            <label for="email" hidden></label><br>
            <input type="email" id="email" autocomplete="on" name="<?= RecoveryPass::POST_NAME_EMAIL; ?>" required value="<?= $email_error ?>">
        </div>

        <div class="item">
            <button type="submit">Отправить</button>
        </div>
    </form>
</div>
