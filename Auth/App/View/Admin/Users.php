<?php
use Auth\App\Entity\User;
use Auth\Sys\Views;

/**
 * @var User[] $users
 * @var $limit
 * @var string $q
 * @var bool $has_not_activated_users
 * @var $user_id_first
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
        <!-- fixme здесь не количество пользователей на этой странице а общее количество пользователей -->
        <span class="total_users"><?= count($users)?> пользователей</span>
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

        <!-- todo если $user_id_first пустой значит больше пользователей нет, а значит эту кнопку показывать не нужно -->
        <form class="wrap_show_more"
              data-<?= \Auth\App\Action\Admin\Users::GET_NAME_USER_ID_FIRST ?>="<?= $user_id_first?>"
              action="<?= \Auth\App\Action\Admin\Users::getUrl() ?>"
              method="get"
        >
            <!-- fixme здесь не должно быть action убрать, action есть в форме выше, если есть какая то проблема, она решается не так -->
            <input type="hidden" name="action" value="Auth\App\Action\Admin\Users">
            <input type="hidden" name="<?= \Auth\App\Action\Admin\Users::GET_NAME_USER_ID_FIRST ?>" value="<?=  $user_id_first?>">
            <button type="submit" class="show_more">
                <span class="more">
                    <!-- fixme здесь не нужно показывать количество пользователей просто "показать еще" -->
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
                return true;

                let btn = $(e.currentTarget);

                let $form = btn.parents('form');

                let user_id_first = $form.data('<?= \Auth\App\Action\Admin\Users::GET_NAME_USER_ID_FIRST ?>');

                $form.find('input[name="<?= \Auth\App\Action\Admin\Users::GET_NAME_USER_ID_FIRST ?>"]').val(user_id_first);

                let q = $form.parents('.b_admin_users').find('#q').val()

                btn.addClass('loading');

                $.ajax({
                    url: $form.attr("action"),
                    method: 'GET',
                    data: { limit: <?= $limit ?>, q: q, user_id_first: user_id_first },
                    success: function(response)
                    {
                        let parser = new DOMParser();

                        let doc = parser.parseFromString(response, 'text/html');

                        let tbody = $(doc).find('.users tbody').html();

						// fixme не нужно колупаться здесь во внутренностях, просто берешь целиком форму "Показать еще" и меняешь на новую
                        let new_user_id_first = $(doc)
                            .find('.wrap_show_more')
                            .data('<?= \Auth\App\Action\Admin\Users::GET_NAME_USER_ID_FIRST ?>');

                        $('.users tbody').append(tbody);

                        $('.wrap_show_more').data('<?= \Auth\App\Action\Admin\Users::GET_NAME_USER_ID_FIRST ?>', new_user_id_first);

						// fixme переместить в complete, сейчас дублирование
                        btn.removeClass('loading');
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
						// fixme 404 это не особый случай, это точно такая же ошибка как любая другая, не нужно ее обрабатывать особым образом
                        //  если мы получили ошибку 404 это это явная ошибка, так как по умолчанию кнопки "Показать еще" просто нету,
                        //  если больше нету пользователей
                        if (jqXHR.status === 404) {
                            $form.hide();
                            console.log('Записей больше нет (404)');
                        } else {
                            // todo текст ошибки здесь должен быть тот который написан на странице ошибке
                            // butterup.toast(
                            //     title: 'Ошибка загрузки данных',
                            //     message: 'Не получилось загрузить пользователей',
                            //     location: 'top-right'
                            // )
                        }

						// fixme переместить в complete, сейчас дублирование
                        btn.removeClass('loading');
                    },
					complete: () =>
					{

                    }
                });

                return false;
            });
        </script>

    </div>

</div>
