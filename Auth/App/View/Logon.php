<?php
/** @var $test */
?>

<div>
    <div class="title">Войти на DriveMusic</div>

    <form  method="post" action="<?= \Auth\App\Action\Logon::getUrl(); ?>">
        <div>
            <label for="login">Имя пользователя или e-mail</label>
            <input type="text" id="login" name="login" required>
        </div>

        <div>
            <div>
                <label for="password">Пароль</label>
                <a>Забыли пароль?</a>
            </div>
            <input type="password" id="password" name="password" required>
        </div>

        <div>
            <button type="submit">Войти</button>
        </div>

        <div>
            <span>Нет аккаунта?</span>
            <a href="<?= \Auth\App\Action\Reg::getUrl(); ?>">Зарегистрируйтесь</a>
        </div>
    </form>
</div>
