
<div>
    <div class="title">Регистрация на DriveMusic</div>

    <form method="post" action="<?= \Auth\App\Action\Reg::getUrl(); ?>">
        <label for="username">Имя пользователя</label>
        <!-- fixme вместе username в имя нужно использовать константу из акшин, и так в каждом input этой форме -->
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