<?php
use Auth\App\Entity\User;
use Auth\Sys\Views;

/**
 * @var User[] $users
 * @var $limit
 * @var string $q
 * @var bool $has_not_activated_users
 * @var $user_id_first
 * @var int $users_count
 */
?>


<div class="b_admin_users">

    <?php
        echo Views::get(
            __DIR__ . '/Users/Search.php',
            [
                'q'  => $q
            ]
        );
    ?>

    <div class="line"></div>

    <div class="toolbar">
        <span class="total_users">Всего <?= $users_count ?> пользователей</span>
        <!-- fixme кнопка ведет на не существующий url ok-->
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
                    <th class="id">ID</th>
                    <th class="date">Дата регистрации</th>
                    <th class="date">Дата входа</th>
                    <!-- todo зафиксируй ширину чтобы таблица "не плава" в зависимости от длины емейла ok-->
                    <th class="email_th">Email</th>
                    <th>Права</th>
                    <!-- todo зафиксируй ширину  ok-->
                    <th class="login">Имя</th>
                    <th class="geo">Гео</th>
                    <th>IP Адрес</th>
                    <!-- todo сделай уже до ширины флажка ok-->
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

        <script >

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
                    .fail((jqXHR) =>
                    {
                        showErrorAjix(jqXHR)
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
                    .done(() => {})
                    .fail((jqXHR, textStatus, errorThrow) =>
                    {
                        $input.prop('checked', false);
                        $input.prop('disabled', false);

						showErrorAjix(jqXHR);
                    })

                return false;
            });
        </script>

        <?php
        echo Views::get(
            __DIR__ . '/Users/ShowMore.php',
            [
                'users'  => $users,
                'limit' => $limit,
                'q' => $q,
                'user_id_first' => $user_id_first
            ]
        );
        ?>
    </div>
</div>
