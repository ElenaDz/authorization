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
            <!-- fixme не плоди новые имена, у нас в имени акшина уже есть имя для этого, используй его -->
            <button class="delete_inactive" type="submit">Удалить не активированных</button>
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
                                    <!-- fixme меняем на то что показано в видео и добавляем live шаблоны -->
                                    <!-- fixme так как наш флаг может только активировать пользователя
                                          и не может снять активацию, нужно добавить disabled, если уже активирован -->
                                    <input
                                        type="checkbox"
                                        name="activation"
                                        value="<?= $user->isActivated() ? 0 : 1; ?>"
	                                    <?= $user->isActivated() ? 'checked' : null; ?>
	                                    <?= $user->isActivated() ? 'onclick="return false;"' : ''; ?>
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
            // fixme нужно вешать событие на таблицу а не на элемент так как таблица будет подгружаться постранично
            // fixme input[type="checkbox"] не достаточно может быть много всяких checkbox в строке, нужно указывать еще и класс
            $('table.users').find('input[type="checkbox"]').on('change',(e) =>
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
						// fixme лучше disabled добавить, а не это
                        $input.attr('onclick', 'return false;');

						// fixme лишнее, в случае успеха флажок останется поставленным, а в случае не удачи снова снимется
                        console.log('Успешная активация пользователя');
                    })
                    .fail(() =>
                    {
						// todo не написать что пытались сделать и что пошло не так и какой получили ответ от сервера
                        throw new Error("Ошибка: Пользователь не активирован");
                    })

                return false;
            });

            // fixme у каждого отдельного элемента должен быть отдельный тег script чтобы их было удобно переносить
            // fixme нужно вешать событие на таблицу, а не на элемент так как таблица будет подгружаться постранично
            $('form.delete').on('submit',(e) =>
            {
                let $form = $(e.currentTarget);

                // fixme искали tr, а получили user_line, не вводи новых названий это только запутывает,
                //  я не знаю ни какой user_line ни где не видел такого имени
                let $user_line = $form.parents('tr');

                $.ajax({
                    url: $form.attr("action"),
                    type: 'POST',
                    data: $form.serialize(),
                })
                    .done(() =>
                    {
                        $user_line.remove();

						// fixme лишнее, и так видно что строка исчезла, а елси ошибка то не исчезла
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
