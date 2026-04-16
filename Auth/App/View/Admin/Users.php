<?php

/**
 * @var User[] $users
 * @var $limit
 * @var $part_email
 */

use Auth\App\Entity\User;
use Auth\Sys\Views;

?>


<div class="b_admin_users">

    <?php
        echo Views::get(
            __DIR__ . '/Users/Search.php',
            [
                'part_email'  => $part_email
            ]
        );
    ?>

    <div class="line"></div>

    <div class="toolbar">
        <span class="total_users"><?= count($users)?> пользователей</span>
        <form action="<?= \Auth\App\Action\DeleteNotActivatedUsers::getUrl() ?>">
            <!-- todo эта кнопка должна быть активна только если в БД действительно есть не активированные пользователи ok-->
            <button class="delete_not_activated"
                    <?= \Auth\App\Model\Users::getNotActivated() ? '' : 'disabled'?>
                    type="submit">
                Удалить не активированных
            </button>
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
                // todo нельзя require, нужно использовать Views::get ok
                echo Views::get(
                    __DIR__ . '/Users/Tbody.php',
                    [
                        'users'  => $users
                    ]
                );
                ?>
            </tbody>
        </table>

        <!-- fixme размести скрипты непосредственно под тем html элементом к которому они относиться, тоесть под таблицей ok-->
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
                        // fixme протестируй, не снимается флажок в случае ошибки, а должен ок
                        $input.prop('checked', false)

                        throw new Error(
                            "Не удалось активировать пользователя. "+
                            "Ошибка: " + errorThrow+". "+
                            "Ответ сервера: " + jqXHR.responseText
                        );
                    })

                return false;
            });
        </script>

        <form class="wrap_show_more" action="<?= \Auth\App\Action\Admin\Users::getUrl()?>" method="post">
            <button class="show_more" data-next_id="">
                <!-- fixme передавай сюда limit чтобы показывать вместо этой цифры 100  ok-->
                Показать ещё <?= $limit ?> пользователей
            </button>
        </form>

        <script>
            $('.show_more').on('click', (e) =>
            {
                let btn = $(e.currentTarget);
                let $form = btn.parents('form');
                let offset = $('.users tbody tr').length;
                let part_email = $form.parents('.b_admin_users').find('#part_email').val()

                btn.text('Загрузка...').prop('disabled', true);

                $.ajax({
                    url: $form.attr("action"),
                    method: 'POST',
                    data: { offset: offset, limit: <?= $limit ?>, part_email: part_email },
                    success: function(response) {
                        if (response.trim() === '') {
                            btn.text('Больше записей нет');
                        } else {
                            $('body').html(response);
                            btn.text('Ещё <?= $limit ?>').prop('disabled', false);
                        }
                    },
                    error: function() {
                        alert('Ошибка загрузки данных');
                        btn.text('Ещё <?= $limit ?>').prop('disabled', false);
                    }
                });

                return false;
            });
        </script>
    </div>

</div>
