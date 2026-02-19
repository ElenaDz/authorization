<?php
use Auth\App\Action\Reg;

/**
 * @var array $errors
 * @var $login
 * @var $email
 */
?>
<div class="title_form">
    <h1>Регистрация на DriveMusic</h1>
    <a class="exit" href="/"></a>
</div>

<form method="post"  action="<?= Reg::getUrl(); ?>">

    <div class="item">
        <label for="login">Имя пользователя</label>
        <!-- fixme пустой атрибут autocomplete, найди все пустые и заполни в соответствии с рекомендациями из сети ok -->
        <input type="text" id="login" autocomplete="on" name="<?= Reg::POST_NAME_LOGIN; ?>" required value="<?= $login ?>" tabindex="1">
        <?php if ( ! empty($errors[Reg::POST_NAME_LOGIN])) :?>
            <small style="color: red;"><?= $errors[Reg::POST_NAME_LOGIN] ?></small>
        <?php endif ?>
    </div>
    <div class="item">
        <label for="email">E-mail</label>
        <input type="email" id="email" autocomplete="on" name="<?= Reg::POST_NAME_EMAIL; ?>" required value="<?= $email ?>" tabindex="2">
        <?php if ( ! empty($errors[Reg::POST_NAME_EMAIL])) :?>
            <small style="color: red;"><?= $errors[Reg::POST_NAME_EMAIL] ?></small>
        <?php endif ?>
    </div>
    <div class="item">
        <label for="pass">Пароль (буквы, цифры, знаки препинания)</label>
        <input type="password" id="pass" name="<?= Reg::POST_NAME_PASS; ?>" required tabindex="3">
        <?php if ( ! empty($errors[Reg::POST_NAME_PASS])) :?>
            <small style="color: red;"><?= $errors[Reg::POST_NAME_PASS]; ?></small>
        <?php endif ?>
    </div>
    <div class="item">
        <label for="pass_confirm">Пароль еще раз</label>
        <input type="password" id="pass_confirm" name="<?= Reg::POST_NAME_PASSWORD_CONFIRM; ?>" required tabindex="4">
    </div>

    <div class="item">
        <button type="submit">Зарегистрироваться</button>
    </div>
</form>