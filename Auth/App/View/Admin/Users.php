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
            <!-- fixme btn в имени класса лишнее, так как это понятно по тегу ок -->
            <button class="delete_inactive" type="submit">Удалить не активированных</button>
        </form>
    </div>

    <div class="table-wrapper">
        <!-- fixme table в имени класса лишнее, так как это понятно по тегу ок-->
        <table class="users">
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
                            <!-- fixme слово user лишнее ок -->
                            <form class="activation" action="<?= \Auth\App\Action\Api\UserActivation::getUrl() ?>" method="post">
                                <!-- fixme hidden всегда располагаются первыми чтобы их точно заметили, так как про них не знаю их не видно ok-->
                                <input
                                        type="hidden"
                                        name="id"
                                        value="<?= $user->getId() ?>"
                                >
                                <!-- fixme кажется лишнее есть ведь label ok -->
                                <label>
                                    <!-- fixme не тот метод использовала  ok-->
                                    <input
                                            type="checkbox"
                                            name="activation"
                                            value="<?= $user->isActivated() ? 0 : 1; ?>"
                                        <?= $user->isActivated() ? 'checked onclick="return false;"' : ''; ?>
                                    >
                                </label>
                            </form>
                        </td>

                        <td class="delete">
                            <!-- fixme слово user лишнее ok -->
                            <form class="delete" action="<?=  \Auth\App\Action\Api\UserDelete::getUrl() ?>" method="post">
                                <input type="hidden" name="id" value="<?= $user->getId() ?>">
                                <!-- fixme слово btn лишнее ok -->
                                <button type="submit">Удалить</button>
                            </form>
                        </td>

                    </tr>

                <?php endforeach; ?>
            </tbody>
        </table>
        <script>
            // fixme здесь нужно перевязываться не к событию отправки формы а к событию смены состояния checkbox ok
            // fixme $('.activation_user') плохо не понятно, понятнее $('form.activation') ok
            // fixme нужно вешать событие на таблицу а не на элемент так как таблица будет подгружаться постранично
            $('table.users').find('input[type="checkbox"]').on('change',(e) =>
            {
                // fixme лучше вместо этого в конце писать return false ok;
                let $input = $(e.currentTarget);

                let $form = $input.parents('form');
                // fixme это очень не понятно, обойдись без этого, состояние checkbox меняется само когда по нему кликаешь, ok
                //  тебе не нужно его менять ok

                $.ajax({
                    url: $form.attr("action"),
                    type: 'POST',
                    data: $form.serialize(),
                })
                    .done(() =>
                    {
                        $input.attr('onclick', 'return false;');
                        console.log('Успешная активация пользователя');
                    })
                    .fail(() =>
                    {
                        throw new Error("Ошибка: Пользователь не активирован");
                    })

                return false;
            })

            // fixme нужно вешать событие на таблицу, а не на элемент так как таблица будет подгружаться постранично
            $('form.delete').on('submit',(e) =>
            {
                // fixme лучше вместо этого в конце писать return false ok;
                let $form = $(e.currentTarget);

                // fixme лишнее, просто удаляем tr родительский ок
                let $user_line = $form.parents('tr');

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
    </div>

</div>

<!-- fixme лучше разместить скрипты сразу после table.users-table  ok-->
