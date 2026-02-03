<?php
use Auth\App\Enum\Error;
use Auth\App\Action\Reg;
/**
 * @var array $errors
 * @var $login
 * @var $email
 */
// fixme в случае ошибки поля должны быть заполнены введенными ранее данными кроме пароля ok
?>
<div>
    <div class="title">Регистрация на DriveMusic</div>

    <form method="post"  action="<?= Reg::getUrl(); ?>">
        <label for="login">Имя пользователя</label>
        <!-- fixme используй константы как я показал, и так в каждом input этой форме  ok-->
        <input type="text" id="login" autocomplete name="<?= Reg::POST_NAME_LOGIN; ?>" required value="<?= $login ?>">
        <?php if ($errors[Error::LOGIN_ERROR]) :?>
        <div><?= $errors[Error::LOGIN_ERROR] ?></div>
        <?php endif ?>

        <label for="email">E-mail</label>
        <input type="email" id="email" autocomplete name="<?= Reg::POST_NAME_EMAIL; ?>" required value="<?= $email ?>">
        <?php if ($errors[Error::EMAIL_ERROR]) :?>
            <div><?= $errors[Error::EMAIL_ERROR] ?></div>
        <?php endif ?>

        <label for="pass">Пароль два раза (буквы, цифры, знаки препинания)</label>
        <input type="password" id="pass" name="<?= Reg::POST_NAME_PASS; ?>" required>
        <?php if ($errors[Error::LIST_PASS_ERROR][Error::PASS_ERROR]) :?>
            <div><?= $errors[Error::LIST_PASS_ERROR][Error::PASS_ERROR]; ?></div>
        <?php endif ?>

        <label for="pass_confirm"></label>
        <input type="password" id="pass_confirm" name="<?= Reg::POST_NAME_PASSWORD_CONFIRM; ?>" required>

        <button type="submit">Зарегистрироваться</button>
    </form>
</div>