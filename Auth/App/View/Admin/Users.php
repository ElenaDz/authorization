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
            <!-- todo disabled не понимает ide а должна -->
            <!-- fixme нельзя обращаться к модели из шаблона -->
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
                echo Views::get(
                    __DIR__ . '/Users/Tbody.php',
                    [
                        'users'  => $users
                    ]
                );
                ?>
            </tbody>
        </table>

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

        <!-- todo не показывать кнопку есть больше пользователей нету -->
        <form class="wrap_show_more" action="<?= \Auth\App\Action\Admin\Users::getUrl()?>" method="post">
            <button class="show_more" data-next_id="">
                Показать ещё <?= $limit ?> пользователей
            </button>
        </form>

        <script>

            $('.show_more').on('click', (e) =>
            {
				// todo !!!! ВНИМАНИЕ !!!  отключаю js до тех пор пока не сделаешь полностью работающую версию без js
                return  true;

                let btn = $(e.currentTarget);
                let $form = btn.parents('form');
                let offset = $('.users tbody tr').length;
                let part_email = $form.parents('.b_admin_users').find('#part_email').val()

                // fixme перед заменой текста на кнопки нужно запомнить предыдущий текст, чтобы можно было потом его вернуть,
                //   но это плохая идея, лучше просто скрывать настоящую надпись и показывать надпись загрузка при добавлении класса loading
                //   а все нужные надписи всегда есть на кнопке
                btn.text('Загрузка...').prop('disabled', true);

                $.ajax({
                    url: $form.attr("action"),
                    method: 'POST',
                    data: { offset: offset, limit: <?= $limit ?>, part_email: part_email },
                    success: function(response) {
						// fixme если записей нет нужно проверять код ответа он будет 404 ну это и в акшине надо запрограммировать
                        if (response.trim() === '') {
							// todo скрываем кнопку, а не меняем текст на ней
                            btn.text('Больше записей нет');

                        } else {
							// fixme мы на заменяем тело страницы, в вставляем присланные строки таблицы в нашу таблицу
                            $('body').html(response);

                            btn.text('Ещё <?= $limit ?>').prop('disabled', false);
                        }
                    },
                    error: function() {
						// todo показываем ошибку с помощью библиотеки, что я прислал
                        alert('Ошибка загрузки данных');
						// fixme не блокируем кнопку, у человека должна быть возможность нажать на нее снова, вдруг заработает
                        btn.text('Ещё <?= $limit ?>').prop('disabled', false);
                    }
                });

                return false;
            });
        </script>

    </div>

</div>
