<?php

/**
 * @var User[] $users
 * @var $limit
 * @var string $q
 * @var bool $has_not_activated_users
 * @var $user_id_first
 */

use Auth\App\Entity\User;
use Auth\Sys\Views;
?>


<div class="b_admin_users">

    <?php
        echo Views::get(
            __DIR__ . '/Users/Search.php',
            [
                'part_email'  => $q
            ]
        );
    ?>

    <div class="line"></div>

    <div class="toolbar">
        <span class="total_users"><?= count($users)?> пользователей</span>
        <form action="<?= \Auth\App\Action\Api\DeleteNotActivatedUsers::getUrl() ?>">
            <!-- todo disabled не понимает ide а должна  ok -->
            <!-- fixme нельзя обращаться к модели из шаблона ok-->
            <button class="delete_not_activated"
                    <?php if ( ! $has_not_activated_users): ?>

                        disabled

                    <?php endif; ?>
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

        <!-- todo не показывать кнопку есть больше пользователей нету ok-->
        <form class="wrap_show_more"
              data-<?= \Auth\App\Action\Admin\Users::GET_NAME_USER_ID_FIRST ?>="<?= $user_id_first?>"
              action="<?= \Auth\App\Action\Admin\Users::getUrl() ?>"
              method="get">
            <!-- todo без аякса метод get меняет урл, при post запросе работает корректно. Сюда урл подставляется некорректно, пришлось  в ручную писать-->
            <input type="hidden" name="action" value="Auth\App\Action\Admin\Users">
            <input type="hidden" name="<?= \Auth\App\Action\Admin\Users::GET_NAME_USER_ID_FIRST ?>" value="<?=  $user_id_first?>">
            <button type="submit" class="show_more">
                <span class="more">
                    Показать ещё <?= $limit ?> пользователей
                </span>
                <span class="inner_loading">
                    Загрузка...
                </span>
            </button>
        </form>

        <script>

            $('.show_more').on('click', (e) =>
            {
				// todo !!!! ВНИМАНИЕ !!!  отключаю js до тех пор пока не сделаешь полностью работающую версию без js

                let btn = $(e.currentTarget);

                let $form = btn.parents('form');

                let user_id_first = $form.data('<?= \Auth\App\Action\Admin\Users::GET_NAME_USER_ID_FIRST ?>');

                $form.find('input[name="<?= \Auth\App\Action\Admin\Users::GET_NAME_USER_ID_FIRST ?>"]').val(user_id_first);

                let q = $form.parents('.b_admin_users').find('#q').val()

                // fixme перед заменой текста на кнопки нужно запомнить предыдущий текст, чтобы можно было потом его вернуть,
                //   но это плохая идея, лучше просто скрывать настоящую надпись и показывать надпись загрузка при добавлении класса loading
                //   а все нужные надписи всегда есть на кнопке ok
                btn.addClass('loading');

                $.ajax({
                    url: $form.attr("action"),
                    method: 'GET',
                    data: { limit: <?= $limit ?>, q: q, user_id_first: user_id_first },
                    success: function(response) {
						// fixme если записей нет нужно проверять код ответа он будет 404 ну это и в акшине надо запрограммировать

                        // fixme мы на заменяем тело страницы, в вставляем присланные строки таблицы в нашу таблицу ok

                        let parser = new DOMParser();

                        let doc = parser.parseFromString(response, 'text/html');

                        let tbody = $(doc).find('.users tbody').html();

                        let new_user_id_first = $(doc)
                            .find('.wrap_show_more')
                            .data('<?= \Auth\App\Action\Admin\Users::GET_NAME_USER_ID_FIRST ?>');

                        $('.users tbody').append(tbody);

                        $('.wrap_show_more').data('<?= \Auth\App\Action\Admin\Users::GET_NAME_USER_ID_FIRST ?>', new_user_id_first);

                        btn.removeClass('loading');

                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        if (jqXHR.status === 404) {
                            $form.hide();
                            console.log('Записей больше нет (404)');
                        } else {
                            // todo показываем ошибку с помощью библиотеки, что я прислал
                            // butterup.toast(
                            //     title: 'Ошибка загрузки данных',
                            //     message: 'Не полуичлось загрузить пользователей',
                            //     location: 'top-right'
                            // )
                        }
						// fixme не блокируем кнопку, у человека должна быть возможность нажать на нее снова, вдруг заработает ok
                        btn.removeClass('loading');
                    }
                });

                return false;
            });
        </script>

    </div>

</div>
