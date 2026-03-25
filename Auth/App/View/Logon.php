<?php

use Auth\App\Action\Logon;
use Auth\App\Action\RecoveryPass;
use Auth\App\Action\Reg;
use Auth\SxGeo\SxGeo;

/**
 * @var array $errors
 * @var $email
 */
?>

<div class="title_form">
    <h1>Войти на DriveMusic</h1>
</div>

<form  method="post" action="<?= Logon::getUrl(); ?>">
    <div class="item">
        <label for="login">E-mail</label>
        <input type="text"
               id="login"
               autocomplete="on"
               name="<?= Logon::POST_NAME_EMAIL; ?>"
               value="<?= htmlspecialchars($email) ?>"
               required
               tabindex="1"
        >
    </div>

    <div class="item">
        <div class="pass_a">
            <label for="pass">Пароль</label>
            <a class="recover_pass_a" href="<?= RecoveryPass::getUrl(); ?>">Забыли пароль?</a>
        </div>
        <input type="password" id="pass" autocomplete="current-password" name="<?= Logon::POST_NAME_PASS; ?>" required tabindex="2">
    </div>

    <div class="item<?= ! empty($errors[Logon::POST_NAME_SUBMIT]) ? ' error_auth' : null; ?>">
        <button type="submit" tabindex="3">Войти</button>
        <?php if ( ! empty($errors) && $errors[Logon::POST_NAME_SUBMIT]) :?>
            <small style="color: red;"><?= $errors[Logon::POST_NAME_SUBMIT]; ?></small>
        <?php endif ?>
    </div>

    <div class="item">
        <div class="reg">
            <span>Нет аккаунта?</span>&nbsp
            <a class="reg_a" href="<?= Reg::getUrl(); ?>">Зарегистрируйтесь</a>
        </div>
    </div>
</form>
