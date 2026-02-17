<?php

use Auth\App\Action\DeleteNotActivatedUsers;
use Auth\App\Action\Logon;
use Auth\App\Action\RecoveryPass;
use Auth\App\Action\Reg;

/**
 * @var array $errors
 * @var $login
 */

// todo форма должна заполнятся ранее введенными данными кроме пароля ok

// todo обрати внимание я добавил tabindex элементам попробуй как он работает с помощью кнопки tab,
//  это очень важно для тех кто не берет мышку во время заполнения формы, например я ok
?>
<!-- fixme это должно быть просто ссылкой, форма здесь ни к чему ok -->
<a href="<?= DeleteNotActivatedUsers::getUrl() ?>">Удалить не активированных пользователей более 7 дней</a><br>

<div>
    <div class="title" style="font-weight: bold">Войти на DriveMusic</div>
    <hr>

    <form  method="post" action="<?= Logon::getUrl(); ?>">
        <div>
            <label for="login">Имя пользователя или e-mail</label><br>
            <input type="text" id="login" autocomplete name="<?= Reg::POST_NAME_LOGIN; ?>" value="<?= $login ?>" required tabindex="1">

        </div>

        <br>
        <div>
            <div>
                <label for="pass">Пароль</label>
                <a href="<?=  RecoveryPass::getUrl(); ?>">Забыли пароль?</a>
            </div>
            <input type="password" id="pass" name="<?= Reg::POST_NAME_PASS; ?>" required tabindex="2">
        </div>

        <br>
        <div>
            <button type="submit" tabindex="3">Войти</button>
	        <?php if (!empty($errors) && $errors[Logon::POST_NAME_SUBMIT]) :?>
                <small style="color: red;"><?= $errors[Logon::POST_NAME_SUBMIT]; ?></small><br>
	        <?php endif ?>
        </div>

        <br>
        <div>
            <span>Нет аккаунта?</span>
            <a href="<?= Reg::getUrl(); ?>">Зарегистрируйтесь</a>
        </div>
    </form>
</div>
