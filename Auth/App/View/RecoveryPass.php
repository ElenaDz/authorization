<?php

use Auth\App\Action\RecoveryPass;


?>

<h1>Восстановить пароль</h1>
<div>
    <span>
        Введите адрес электронной почты, указанный при регистрации вашего аккаунта. Мы направим ссылку для сброса пароля.
    </span>

    <form method="post"  action="<?=  RecoveryPass::getUrl(); ?>">
        <label for="email"></label><br>
        <input type="email" id="email" autocomplete name="<?= RecoveryPass::POST_NAME_EMAIL; ?>" required value="">

        <button type="submit">Отправить</button>
    </form>
</div>
