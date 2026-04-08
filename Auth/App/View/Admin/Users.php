<?php

/**
 * @var User[] $users
 */

use Auth\App\Entity\User;
?>


<!-- fixme переименовать в b_admin_users ок-->
<div class="b_admin_users">

    <!-- fixme переименовать в toolbar ок-->
    <div class="toolbar">
        <span class="total_users"><?= count($users)?> пользователей</span>
        <form action="<?= \Auth\App\Action\DeleteNotActivatedUsers::getUrl() ?>">
            <button class="btn-delete-inactive" type="submit">Удалить не активированных</button>
        </form>
    </div>

    <div class="table-wrapper">
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
                            <form class="activation_user" action="<?= \Auth\App\Action\Api\UserSetActivation::getUrl() ?>" method="post">
                                <div class="wrap_active">
                                    <label>
                                        <?php if ($user->getActivationCode()): ?>
                                            <button type="submit"></button>

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
                                <input type="hidden"
                                       name="id"
                                       value="<?= $user->getId() ?>">
                            </form>
                        </td>

                        <td class="delete">
                            <form class="delete_user" action="<?=  \Auth\App\Action\Api\UserDelete::getUrl() ?>" method="post">
                                <input type="hidden" name="id" value="<?= $user->getId() ?>">
                                <button class="btn-delete" type="submit">Удалить</button>
                            </form>
                        </td>
                    </tr>

                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<script>
    $('.activation_user').on('submit',(e) =>
    {
        e.preventDefault();

        let $form = $(e.currentTarget);

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

    $('.delete_user').on('submit',(e) =>
    {
        e.preventDefault();

        let $form = $(e.currentTarget);

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