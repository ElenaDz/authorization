<?php
use Auth\App\Action\ChangePass;

/**
 * @var $email
 * @var $activation_link
 */
?>
<div>
    <form method="post"  action="<?= $activation_link; ?>">

        <label for="email">E-mail</label><br>
        <input type="email" id="email" autocomplete name="<?= ChangePass::POST_NAME_EMAIL; ?>" required value="<?= $email ?>"><br>

        <br>
        <label for="pass">Пароль (буквы, цифры, знаки препинания)</label><br>
        <input type="password" id="pass" name="<?= ChangePass::POST_NAME_PASS; ?>" required><br>

        <label for="pass_confirm">Пароль еще раз</label>
        <input type="password" id="pass_confirm" name="<?= ChangePass::POST_NAME_PASSWORD_CONFIRM; ?>" required><br>

        <br>
        <button type="submit">Сменить пароль</button>
    </form>
</div>
