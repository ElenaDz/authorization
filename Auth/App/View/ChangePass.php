<?php
use Auth\App\Action\ChangePass;

/**
 * @var $email
 * @var $change_pass_link
 * @var array $errors
 */
?>
<div class="title_form">
    <h1>Смена пароля</h1>
    <a class="exit" href="/"></a>
</div>

<form method="post"  action="<?= $change_pass_link; ?>">

    <div class="item">
        <label for="email">E-mail</label>
        <input type="email"
            id="email"
            autocomplete="on"
            name="<?= ChangePass::POST_NAME_EMAIL; ?>"
            required
            value="<?= $email ?>"
            tabindex="1"
        >
    </div>

    <div class="item">
        <label for="pass">Пароль (буквы, цифры, знаки препинания)</label>
        <input type="password" id="pass" name="<?= ChangePass::POST_NAME_PASSWORD; ?>" required tabindex="2">
        <?php if (!empty($errors[ChangePass::POST_NAME_PASSWORD])) :?>
            <small style="color: red;"><?= $errors[ChangePass::POST_NAME_PASSWORD]; ?></small>
        <?php endif ?>
    </div>

    <div class="item">
        <label for="pass_confirm">Пароль еще раз</label>
        <input type="password" id="pass_confirm" name="<?= ChangePass::POST_NAME_PASSWORD_CONFIRM; ?>" required tabindex="3">
    </div>

    <div class="item">
        <button type="submit">Сменить пароль</button>
    </div>

</form>


