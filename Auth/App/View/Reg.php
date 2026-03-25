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
</div>

<form method="post"  action="<?= Reg::getUrl(); ?>">

    <div class="item<?= ! empty($errors[Reg::POST_NAME_LOGIN]) ? ' error_auth' : null; ?>">
        <label for="login">Имя пользователя</label>
        <input type="text" id="login" autocomplete="on" name="<?= Reg::POST_NAME_LOGIN; ?>" required placeholder="Username" value="<?= htmlspecialchars($login) ?>" tabindex="1">
        <?php if ( ! empty($errors[Reg::POST_NAME_LOGIN])) :?>
            <small style="color: red;"><?= $errors[Reg::POST_NAME_LOGIN] ?></small>
        <?php endif ?>
    </div>
    <div class="item<?= ! empty($errors[Reg::POST_NAME_EMAIL]) ? ' error_auth' : null; ?>">
        <label for="email">E-mail</label>
        <input type="email" id="email" autocomplete="on" name="<?= Reg::POST_NAME_EMAIL; ?>" required placeholder="username@mail.ru" value="<?= htmlspecialchars($email) ?>" tabindex="2">
        <?php if ( ! empty($errors[Reg::POST_NAME_EMAIL])) :?>
            <small style="color: red;"><?= $errors[Reg::POST_NAME_EMAIL] ?></small>
        <?php endif ?>
    </div>
    <div class="item<?= ! empty($errors[Reg::POST_NAME_PASS]) ? ' error_auth' : null; ?>">
        <label for="pass">Пароль (буквы, цифры, знаки препинания)</label>
        <input type="password" id="pass" autocomplete="new-password" name="<?= Reg::POST_NAME_PASS; ?>" required placeholder="********" tabindex="3">
        <?php if ( ! empty($errors[Reg::POST_NAME_PASS])) :?>
            <small style="color: red;"><?= $errors[Reg::POST_NAME_PASS]; ?></small>
        <?php endif ?>
    </div>
    <div class="item">
        <label for="pass_confirm">Пароль еще раз</label>
        <input type="password" id="pass_confirm" autocomplete="new-password" name="<?= Reg::POST_NAME_PASSWORD_CONFIRM; ?>" required placeholder="********" tabindex="4">
    </div>

    <div class="item">
        <button type="submit">Зарегистрироваться</button>
    </div>
</form>