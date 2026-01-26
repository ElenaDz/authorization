
<!-- fixme файл не используется -->

<div>
    <div class="title">Регистрация на DriveMusic</div>

    <form method="post" action="<?= \Auth\App\Action\Reg::getUrl(); ?>">
        <label for="username">Имя пользователя</label>
        <input type="text" id="username" name="username" required>

        <label for="email">E-mail</label>
        <input type="email" id="email" name="email" required>

        <label for="password">Пароль два раза</label>
        <input type="password" id="password" name="password" required>

        <label for="password_confirm"></label>
        <input type="password" id="password_confirm" name="password_confirm" required>

        <button type="submit">Зарегистрироваться</button>
    </form>
</div>