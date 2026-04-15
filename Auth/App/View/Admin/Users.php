<?php

/**
 * @var User[] $users
 */

use Auth\App\Entity\User;
?>


<div class="b_admin_users">
    <div class="search">
        <form action="<?= \Auth\App\Action\Admin\ShowUserByEmail::getUrl()?>" method="post">
            <label for="part_email">
                Поиск по e-mail:
            </label>
            <input type="text" id="part_email">
        </form>
    </div>

    <div class="line"></div>

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
                <?php require __DIR__ . '/UserTr.php'; ?>
            </tbody>
        </table>

        <form class="wrap_show_more" action="<?= \Auth\App\Action\Admin\ShowMoreUsers::getUrl()?>" method="post">
            <button class="show_more">
                Показать ещё 100 пользователей
            </button>
        </form>

        <script>
            $('.search input').on('keydown', (e) =>
            {
                if (e.key !== 'Enter') return ;

                let $input = $(e.currentTarget);
                let $form = $input.parents('form');
                let part_email = $input.val();

                $.ajax({
                    url: $form.attr("action"),
                    method: 'POST',
                    data: { part_email: part_email, },
                    success: function(response) {
                        $('.users tbody').html(response);
                    },
                    error: function() {
                        alert('Ошибка');
                    }
                });

                return false;
            });
        </script>

        <script>
            $('.show_more').on('click', (e) =>
            {
                let btn = $(e.currentTarget);
                let $form = btn.parents('form');
                let offset = $('.users tbody tr').length;

                btn.text('Загрузка...').prop('disabled', true);

                $.ajax({
                    url: $form.attr("action"),
                    method: 'POST',
                    data: { offset: offset, limit: 2 },
                    success: function(response) {
                        if (response.trim() === '') {
                            btn.text('Больше записей нет');
                        } else {
                            $('.users tbody').append(response);
                            btn.text('Ещё 100').prop('disabled', false);
                        }
                    },
                    error: function() {
                        alert('Ошибка загрузки данных');
                        btn.text('Ещё 100').prop('disabled', false);
                    }
                });

                return false;
            });
        </script>

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
                    .fail((jqXHR, textStatus, errorThrow) =>
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
