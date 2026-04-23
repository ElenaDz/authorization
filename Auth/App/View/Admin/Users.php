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
        <!-- fixme здесь не количество пользователей на этой странице а общее количество пользователей ок-->
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

    <?php if (empty($users)): ?>

        Пусто

    <?php return false; ?>
    <?php endif; ?>
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

        <!-- fixme этот скрипт должен находиться под table ок-->
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

        <!-- todo если $user_id_first пустой значит больше пользователей нет, а значит эту кнопку показывать не нужно ok-->
        <form class="wrap_show_more"
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
                    <!-- fixme здесь не нужно показывать количество пользователей просто "показать еще" ок-->
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
                    // todo !!!! ВНИМАНИЕ !!!  отключаю js до тех пор пока не сделаешь полностью работающую версию без js ok
                    // return true;

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

                            let $wrap_show_more =  $(doc).find('.wrap_show_more');

                            $('.wrap_show_more').replaceWith($wrap_show_more);

                            initShowMore();
                            // fixme не нужно колупаться здесь во внутренностях, просто берешь целиком форму "Показать еще" и меняешь на новую ок

                            $('.users tbody').append(tbody);

                            // fixme переместить в complete, сейчас дублирование ok
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            // fixme 404 это не особый случай, это точно такая же ошибка как любая другая, не нужно ее обрабатывать особым образом
                            //  если мы получили ошибку 404 это это явная ошибка, так как по умолчанию кнопки "Показать еще" просто нету,
                            //  если больше нету пользователей

                            // todo текст ошибки здесь должен быть тот который написан на странице ошибке
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

            initShowMore();
        </script>

    </div>

</div>
