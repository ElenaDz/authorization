<?php

/**
 * @var User[] $users
 */

use Auth\App\Entity\User;
?>


<div class="b_admin_users">

    <!-- todo вынеси блок поиска в отдельный шаблон в папку Users -->
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
            <!-- todo эта кнопка должна быть активна только если в БД действительно есть не активированные пользователи -->
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
                <?php
                // todo нельзя require, нужно использовать Views::get
                require __DIR__ . '/Users/Tbody.php';
                ?>
            </tbody>
        </table>

        <form class="wrap_show_more" action="<?= \Auth\App\Action\Admin\ShowMoreUsers::getUrl()?>" method="post">
            <button class="show_more">
                <!-- fixme передавай сюда limit чтобы показывать вместо этой цифры 100 -->
                Показать ещё 100 пользователей
            </button>
        </form>

        <!-- fixme размести скрипт непосредственно под тем html элементом к которому он относиться -->
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

        <!-- fixme размести скрипты непосредственно под тем html элементом к которому они относиться, тоесть под таблицей -->
        <script>
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
                        $input.prop('disabled', true);
                    })
                    .fail((jqXHR, textStatus, errorThrow) =>
                    {
						// fixme протестируй, не снимается флажок в случае ошибки, а должен
                        throw new Error(
							"Не удалось активировать пользователя. "+
                            "Ошибка: " + errorThrow+". "+
                            "Ответ сервера: " + jqXHR.responseText
                        );
                    })

                return false;
            });
        </script>

        <script>
            $('table.users').on('submit', '.delete', (e) =>
            {
                let $form = $(e.currentTarget);

                let $tr = $form.parents('tr');

				// todo подтверждать удаление с помощью confirm('Удалить пользователя <user name>')

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
                    });

                return false;
            })
        </script>
    </div>

</div>
