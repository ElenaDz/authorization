<?php
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
        <input type="text" id="login" autocomplete name="<?= Reg::POST_NAME_LOGIN; ?>" required value="<?= $login ?>" tabindex="1"><br>
        <?php if (!empty($errors[Reg::POST_NAME_LOGIN])) :?>
            <small style="color: red;"><?= $errors[Reg::POST_NAME_LOGIN] ?></small>
        <?php endif ?>

        <br>
        <label for="email">E-mail</label><br>
        <input type="email" id="email" autocomplete name="<?= Reg::POST_NAME_EMAIL; ?>" required value="<?= $email ?>" tabindex="2"><br>
        <?php if (!empty($errors[Reg::POST_NAME_EMAIL])) :?>
            <small style="color: red;"><?= $errors[Reg::POST_NAME_EMAIL] ?></small><br>
        <?php endif ?>

        <br>
        <label for="pass">Пароль (буквы, цифры, знаки препинания)</label><br>
        <input type="password" id="pass" name="<?= Reg::POST_NAME_PASS; ?>" required tabindex="3"><br>
        <?php if (!empty($errors[Reg::POST_NAME_PASS])) :?>
            <small style="color: red;"><?= $errors[Reg::POST_NAME_PASS]; ?></small><br>
        <?php endif ?>

        <br>
        <label for="pass_confirm">Пароль еще раз</label><br>
        <input type="password" id="pass_confirm" name="<?= Reg::POST_NAME_PASSWORD_CONFIRM; ?>" required tabindex="4"><br>

        <br>
        <button type="submit">Зарегистрироваться</button>
    </form>
</div>