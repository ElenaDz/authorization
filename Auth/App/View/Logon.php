<?php

use Auth\App\Action\DeleteNotActivatedUsers;
use Auth\App\Action\Logon;
use Auth\App\Action\RecoveryPass;
use Auth\App\Action\Reg;

/**
 * @var array $errors
 */
// todo не вижу здесь сообщений об ошибках, на каждый запрос пользователя если он что то ввел нужно либо авторизация
//  либо сообщение об ошибке ok
?>
<!-- для теста-->
<form method="post" action="<?= DeleteNotActivatedUsers::getUrl() ?>">
    <button type="submit">Удалить неактивированых пользователей более 7 дней</button>
</form><br>

<div>
    <div class="title" style="font-weight: bold">Войти на DriveMusic</div>
    <hr>

    <form  method="post" action="<?= Logon::getUrl(); ?>">
        <div>
            <label for="login">Имя пользователя или e-mail</label><br>
            <input type="text" id="login" name="<?= Reg::POST_NAME_LOGIN; ?>" required>

        </div>

        <br>
        <div>
            <div>
                <label for="pass">Пароль</label>
                <a href="<?=  RecoveryPass::getUrl(); ?>">Забыли пароль?</a>
            </div>
            <input type="password" id="pass" name="<?= Reg::POST_NAME_PASS; ?>" required>
        </div>

        <br>
        <div>
            <?php if (!empty($errors) && $errors[Logon::POST_NAME_SUBMIT]) :?>
                <small style="color: red;"><?= $errors[Logon::POST_NAME_SUBMIT]; ?></small><br>
            <?php endif ?>
            <button type="submit">Войти</button>
        </div>

        <br>
        <div>
            <span>Нет аккаунта?</span>
            <a href="<?= Reg::getUrl(); ?>">Зарегистрируйтесь</a>
        </div>
    </form>
</div>
