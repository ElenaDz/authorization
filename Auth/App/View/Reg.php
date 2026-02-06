<?php
// fixme вернуть где был, эта папка для блоков (кусочков из которых состоит страница), а это целая страница
//  она должен быть в корне папки шаблоны ok
use Auth\App\Enum\Error;
use Auth\App\Action\Reg;
/**
 * @var array $errors
 * @var $login
 * @var $email
 */
?>
<div>
    <div class="title" style="font-weight: bold;">Регистрация на DriveMusic</div>
    <hr>

    <form method="post"  action="<?= Reg::getUrl(); ?>">
        <label for="login">Имя пользователя</label><br>
        <input type="text" id="login" autocomplete name="<?= Reg::POST_NAME_LOGIN; ?>" required value="<?= $login ?>"><br>
        <?php if ($errors[Error::LOGIN_ERROR]) :?>
            <div style="color: red;"><?= $errors[Error::LOGIN_ERROR] ?></div>
        <?php endif ?>

        <br>
        <label for="email">E-mail</label><br>
        <input type="email" id="email" autocomplete name="<?= Reg::POST_NAME_EMAIL; ?>" required value="<?= $email ?>"><br>
        <?php if ($errors[Error::EMAIL_ERROR]) :?>
            <div style="color: red;"><?= $errors[Error::EMAIL_ERROR] ?></div>
        <?php endif ?>

        <br>
        <label for="pass">Пароль два раза (буквы, цифры, знаки препинания)</label><br>
        <input type="password" id="pass" name="<?= Reg::POST_NAME_PASS; ?>" required><br>
        <?php if ($errors[Error::LIST_PASS_ERROR][Error::PASS_ERROR]) :?>
            <div style="color: red;"><?= $errors[Error::LIST_PASS_ERROR][Error::PASS_ERROR]; ?></div>
        <?php endif ?>

        <label for="pass_confirm"></label>
        <input type="password" id="pass_confirm" name="<?= Reg::POST_NAME_PASSWORD_CONFIRM; ?>" required><br>

        <br>
        <button type="submit">Зарегистрироваться</button>
    </form>
</div>