<?php
/** @var $login */
/** @var $pass */
/** @var $pass_confirm */
/** @var $email */
?>

<div>
    <div class="title">Регистрация на DriveMusic</div>

    <form method="post" action="<?= \Auth\App\Action\Reg::getUrl(); ?>">
        <label for="<?= $login; ?>">Имя пользователя</label>
        <!-- fixme вместе username в имя нужно использовать константу из акшин, и так в каждом input этой форме  ok-->
        <input type="text" id="<?= $login; ?>" name="<?= $login; ?>" required>

        <label for="<?= $email; ?>">E-mail</label>
        <input type="email" id="<?= $email; ?>" name="<?= $email; ?>" required>

        <label for="<?= $pass; ?>">Пароль два раза (буквы, цифры, знаки препинания)</label>
        <input type="password" id="<?= $pass; ?>" name="<?= $pass; ?>" required>

        <label for="<?= $pass_confirm; ?>"></label>
        <input type="password" id="<?= $pass_confirm; ?>" name="<?= $pass_confirm; ?>" required>

        <button type="submit">Зарегистрироваться</button>
    </form>
</div>