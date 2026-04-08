<?php

/**
 * @var User[] $users
 */

use Auth\App\Entity\User;
?>


<div class="b_admin_users">

    <div class="toolbar">
        <span class="total_users"><?= count($users)?> пользователей</span>
        <form action="<?= \Auth\App\Action\DeleteNotActivatedUsers::getUrl() ?>">
            <!-- fixme btn в имени класса лишнее, так как это понятно по тегу -->
            <button class="btn-delete-inactive" type="submit">Удалить не активированных</button>
        </form>
    </div>

    <div class="table-wrapper">
        <!-- fixme table в имени класса лишнее, так как это понятно по тегу -->
        <table class="users-table">
            <thead>
                <tr>
                    <th class="date">Дата регистрации</th>
                    <th class="date">Дата входа</th>
                    <th class="email_th">Email</th>
                    <th>Права</th>
                    <th class="login">Имя</th>
                    <th class="geo">Гео</th>
                    <th>IP Адрес</th>
                    <th class="activation">Активация</th>
                    <th class="delete">Действия</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user) : ?>

                    <tr data-user_id="<?= $user->getId() ?>">

                        <td class="date"><?= $user->getCreatedAt() ?></td>
                        <td class="date"><?= $user->getLastLoginAt() ?></td>
                        <td class="email"><?= $user->getEmail() ?></td>

                        <td class="role">
                            <form>
                                <label>
                                    <select>
                                        <option>Пользователь</option>
                                    </select>
                                </label>
                            </form>
                        </td>

                        <td class="login"><?= $user->getLogin() ?></td>
                        <td class="geo"><?= $user->getCountry() ?> <br> <?= $user->getCity() ?></td>
                        <td class="ip"><?= $user->getIP() ?></td>

                        <td class="activation">
                            <!-- fixme слово user лишнее -->
                            <form class="activation_user" action="<?= \Auth\App\Action\Api\UserActivation::getUrl() ?>" method="post">
                                <!-- fixme кажется лишнее есть ведь label -->
                                <div class="wrap_active">
                                    <label>
                                        <!-- fixme не тот метод использовала  -->
                                        <?php if ($user->getActivationCode()): ?>

                                            <!-- fixme не понял зачем эта кнопка здесь, удалить -->
                                            <button type="submit"></button>

                                            <!-- fixme дублирование, input name="active" должен быть один -->
                                            <input
                                                type="checkbox"
                                                name="active"
                                                value=0
                                            >

                                        <?php else: ?>

                                            <input
                                                type="checkbox"
                                                name="active"
                                                value=1
                                                checked
                                                onclick="return false;"
                                            >

                                        <?php endif; ?>

                                    </label>
                                </div>
                                <!-- fixme hidden всегда располагаются первыми чтобы их точно заметили, так как про них не знаю их не видно -->
                                <input
                                    type="hidden"
                                    name="id"
                                    value="<?= $user->getId() ?>"
                                >
                            </form>
                        </td>

                        <td class="delete">
                            <!-- fixme слово user лишнее -->
                            <form class="delete_user" action="<?=  \Auth\App\Action\Api\UserDelete::getUrl() ?>" method="post">
                                <input type="hidden" name="id" value="<?= $user->getId() ?>">
                                <!-- fixme слово btn лишнее -->
                                <button class="btn-delete" type="submit">Удалить</button>
                            </form>
                        </td>

                    </tr>

                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

</div>

<!-- fixme лучше разместить скрипты сразу после table.users-table  -->
<script>
    // fixme здесь нужно перевязываться не к событию отправки формы а к событию смены состояния checkbox
    // fixme $('.activation_user') плохо не понятно, понятнее $('form.activation')
    // fixme нужно вешать событие на таблицу а не на элемент так как таблица будет подгружаться постранично
    $('.activation_user').on('submit',(e) =>
    {
		// fixme лучше вместо этого в конце писать return false;
        e.preventDefault();

        let $form = $(e.currentTarget);

		// fixme это очень не понятно, обойдись без этого, состояние checkbox меняется само когда по нему кликаешь,
        //  тебе не нужно его менять
        $form.find('input[type="checkbox"]').prop('checked', true);

        $.ajax({
            url: $form.attr("action"),
            type: 'POST',
            data: $form.serialize(),
        })
            .done(() =>
            {
                console.log('Успешная активация пользователя')
            })
            .fail(() =>
            {
                $form.find('input[type="checkbox"]').prop('checked', false);
                throw new Error("Ошибка: Пользователь не активирован");
            })

        return false;
    })

	// fixme нужно вешать событие на таблицу, а не на элемент так как таблица будет подгружаться постранично
    $('.delete_user').on('submit',(e) =>
    {
		// fixme лучше вместо этого в конце писать return false;
        e.preventDefault();

        let $form = $(e.currentTarget);

		// fixme лишнее, просто удаляем tr родительский
        //   let $tr = $form.parents('tr');
        let user_id = $form.find('input[name="id"]').val();

        let $user_line = $(`tr[data-user_id="${user_id}"]`)

        $.ajax({
            url: $form.attr("action"),
            type: 'POST',
            data: $form.serialize(),
        })
            .done(() =>
            {
                $user_line.remove();

                console.log('Пользователь успешно удалён')
            })
            .fail(() =>
            {
                throw new Error("Ошибка: Пользователь не удалён");
            })

        return false;
    })
</script>