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
            <!-- fixme не плоди новые имена, у нас в имени акшина уже есть имя для этого, используй его ok-->
            <button class="delete_not_activated" type="submit">Удалить не активированных</button>
        </form>
    </div>

    <div class="table-wrapper">
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
                            <form class="activation" action="<?= \Auth\App\Action\Api\UserActivation::getUrl() ?>" method="post">
                                <input
                                    type="hidden"
                                    name="id"
                                    value="<?= $user->getId() ?>"
                                >
                                <label>
                                    <!-- fixme меняем на то что показано в видео и добавляем live шаблоны ok-->
                                    <!-- fixme так как наш флаг может только активировать пользователя
                                          и не может снять активацию, нужно добавить disabled, если уже активирован ok -->
                                    <input
                                        type="checkbox"
                                        name="activation"
                                        value="1"
                                        <?php if ($user->isActivated()): ?>
                                            checked
                                        <?php endif; ?>

                                        <?php if ($user->isActivated()): ?>
                                            disabled
                                        <?php endif; ?>
                                    >
                                </label>
                            </form>
                        </td>

                        <td class="delete">
                            <form class="delete" action="<?=  \Auth\App\Action\Api\UserDelete::getUrl() ?>" method="post">
                                <input type="hidden" name="id" value="<?= $user->getId() ?>">
                                <button type="submit">Удалить</button>
                            </form>
                        </td>

                    </tr>

                <?php endforeach; ?>
            </tbody>
        </table>

        <script>
            // fixme нужно вешать событие на таблицу а не на элемент так как таблица будет подгружаться постранично ok
            // fixme input[type="checkbox"] не достаточно может быть много всяких checkbox в строке, нужно указывать еще и класс ok
            $('table.users').on('change', '.activation input[type="checkbox"]',(e) =>
            {
                let $input = $(e.currentTarget);

                let $form = $input.parents('form');

                $.ajax({
                    url: $form.attr("action"),
                    type: 'POST',
                    data: $form.serialize(),
                })
                    .done(() =>
                    {
						// fixme лучше disabled добавить, а не это ok
                        $input.prop('disabled', true);
                    })
                    .fail((jqXHR: JQueryXHR, textStatus: string, errorThrow: string) =>
                    {
						// todo не написать что пытались сделать и что пошло не так и какой получили ответ от сервера ok
                        throw new Error
                        ("Не удалось активировать пользователя."
                            + "Ошибка: " + errorThrow
                            + "Ответ сервера: " + jqXHR.responseText);
                    })

                return false;
            });
        </script>

        <script>
            // fixme у каждого отдельного элемента должен быть отдельный тег script чтобы их было удобно переносить ok
            // fixme нужно вешать событие на таблицу, а не на элемент так как таблица будет подгружаться постранично ok
            $('table.users').on('submit', '.delete', (e) =>
            {
                let $form = $(e.currentTarget);

                // fixme искали tr, а получили user_line, не вводи новых названий это только запутывает, ok
                //  я не знаю ни какой user_line ни где не видел такого имени ok
                let $tr = $form.parents('tr');

                $.ajax({
                    url: $form.attr("action"),
                    type: 'POST',
                    data: $form.serialize(),
                })
                    .done(() =>
                    {
                        $tr.remove();
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
