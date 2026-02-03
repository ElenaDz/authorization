<?php
use Auth\App\Action\Logon;
use Auth\App\Action\Reg;
?>

<div>
    <div class="title">Войти на DriveMusic</div>

    <form  method="post" action="<?= Logon::getUrl(); ?>">
        <div>
            <label for="login">Имя пользователя или e-mail</label>
            <!-- fixme исправить, сделать как в форме регистрации  ok -->
            <input type="text" id="login" name="<?= Reg::POST_NAME_LOGIN; ?>" required>
        </div>

        <div>
            <div>
                <label for="pass">Пароль</label>
                <a>Забыли пароль?</a>
            </div>
            <input type="password" id="pass" name="<?= Reg::POST_NAME_PASS; ?>" required>
        </div>

        <div>
            <button type="submit">Войти</button>
        </div>

        <div>
            <span>Нет аккаунта?</span>
            <a href="<?= Reg::getUrl(); ?>">Зарегистрируйтесь</a>
        </div>
    </form>
</div>
