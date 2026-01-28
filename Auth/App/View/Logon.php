<?php
/** @var $login */
/** @var $pass */
?>

<div>
    <div class="title">Войти на DriveMusic</div>

    <form  method="post" action="<?= \Auth\App\Action\Logon::getUrl(); ?>">
        <div>
            <label for="<?= $login; ?>">Имя пользователя или e-mail</label>
            <!-- fixme вместе login в имя нужно использовать константу из акшин, и так в каждом input этой форме ok -->
            <input type="text" id="<?= $login; ?>" name="<?= $login; ?>" required>
        </div>

        <div>
            <div>
                <label for="<?= $pass; ?>">Пароль</label>
                <a>Забыли пароль?</a>
            </div>
            <input type="password" id="<?= $pass; ?>" name="<?= $pass; ?>" required>
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
