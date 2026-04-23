<?php
use Auth\App\Entity\User;
use Auth\Sys\Views;

/**
 * @var User[] $users
 * @var $limit
 * @var string $q
 * @var bool $has_not_activated_users
 * @var $user_id_first
 * @var bool $has_users_more
 * @var int $users_count
 */
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
        <span class="total_users">Всего <?= $users_count ?> пользователей</span>
        <form action="<?= \Auth\App\Action\Api\DeleteNotActivatedUsers::getUrl() ?>">

            <button class="delete_not_activated"
                <?php if ( ! $has_not_activated_users): ?>

                    disabled

                <?php endif; ?>
                type="submit"
            >
                Удалить не активированных
            </button>
        </form>
    </div>


    <div class="table-wrapper">

        <table class="users">
            <thead>
                <tr>
                    <th class="date">ID</th>
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
            $('table.users').on('submit', '.delete', (e) =>
            {
                let $form = $(e.currentTarget);

                let $tr = $form.parents('tr');

                let user_login = $tr.find('.login').text();

                if ( ! confirm(`Удалить пользователя ${user_login}?`)) {
                    return false;
                }

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
                        // todo использовать библиотеку нотификаций, создать глобальную фукнцию чтобы не писать одно и тоже везде,
                        //  а использовать эту функцию для показа ошибки
                        throw new Error("Ошибка: Пользователь не удалён");
                    });

                return false;
            })
        </script>

        <script>
            $('table.users').on('change', '.activation input[type="checkbox"]',(e) =>
            {
                let $input = $(e.currentTarget);

                let $form = $input.parents('form');

                let form_serialize = $form.serialize();

                $input.prop('disabled', true);

                $.ajax({
                    url: $form.attr("action"),
                    type: 'POST',
                    data: form_serialize,
                })
                    .done(() =>
                    {})
                    .fail((jqXHR, textStatus, errorThrow) =>
                    {
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

        <!-- todo здесь нужен не display: none а if ом обернуть весь блок -->
        <!-- fixme проверять нужно $user_id_first а $has_users_more вообще не нужен -->
        <!-- todo вынести в отдельный блок -->
        <!-- fixme не вижу где есть поисковый запрос -->
        <form
            class="wrap_show_more"
            data-<?= \Auth\App\Action\Admin\Users::GET_NAME_USER_ID_FIRST ?>="<?= $user_id_first?>"
            action="<?= \Auth\App\Action\Admin\Users::getUrl() ?>"
            method="get"
            <?php if ( ! $has_users_more): ?>

              style="display: none"

            <?php endif; ?>
        >
            <input type="hidden" name="action" value="<?= \Auth\App\Action\Admin\Users::class; ?>">
            <input type="hidden" name="<?= \Auth\App\Action\Admin\Users::GET_NAME_USER_ID_FIRST ?>" value="<?= $user_id_first?>">
            <button type="submit" class="show_more">
                <span class="more">
                    Показать ещё
                </span>
                <span class="inner_loading">
                    Загрузка...
                </span>
            </button>
        </form>

        <script>
            function initShowMore() {
                $('.show_more').on('click', (e) =>
                {
                    let btn = $(e.currentTarget);

                    let $form = btn.parents('form');

                    let user_id_first = $form.data('<?= \Auth\App\Action\Admin\Users::GET_NAME_USER_ID_FIRST ?>');

                    $form.find('input[name="<?= \Auth\App\Action\Admin\Users::GET_NAME_USER_ID_FIRST ?>"]').val(user_id_first);

					// fixme так не пойдет, поисковый запрос должен быть в форме даже без js
                    let q = $form.parents('.b_admin_users').find('#q').val()

                    btn.addClass('loading');

					// fixme js нет необходимости знать детали того что именно передавать он должен передавать все что есть в форме
                    $.ajax({
                        url: $form.attr("action"),
                        method: 'GET',
                        data: { limit: <?= $limit ?>, q: q, user_id_first: user_id_first },
                        success: function(response)
                        {
                            let parser = new DOMParser();

                            let doc = parser.parseFromString(response, 'text/html');

                            let tbody = $(doc).find('.users tbody').html();

                            let $wrap_show_more =  $(doc).find('.wrap_show_more');

                            $('.wrap_show_more').replaceWith($wrap_show_more);

							// fixme это хрупкий способ инициализации который может привести к повторному вешанью событий,
                            //   необходимо использовать подход такой же как с кнопками удалить
                            initShowMore();

                            $('.users tbody').append(tbody);
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            // todo текст ошибки здесь должен быть тот который написан на странице ошибки
                            // butterup.toast(
                            //     title: 'Ошибка загрузки данных',
                            //     message: 'Не получилось загрузить пользователей',
                            //     location: 'top-right'
                            // )
                        },
                        complete: () =>
                        {
                            btn.removeClass('loading');
                        }
                    });

                    return false;
                });
            }

			// fixme избавляемся от этой функции ее не должно быть, возле другого ее вызова написал альтернативу
            initShowMore();
        </script>

    </div>

</div>
