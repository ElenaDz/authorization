<?php

/**
 * @var User[] $users
 */

use Auth\App\Entity\User;

?>

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
                <!-- fixme использовать константы для name ok-->
                <input
                    type="hidden"
                    name="<?= \Auth\App\Action\Admin\Users::POST_NAME_ID ?>"
                    value="<?= $user->getId() ?>"
                >
                <label>
                    <!-- fixme использовать константы для name ok-->
                    <!-- fixme не совпадают имена activation и isActivated, лучше чтобы полностью совпадали, чтобы не было вопросов ok-->
                    <input
                        type="checkbox"
                        name="<?= \Auth\App\Action\Admin\Users::POST_NAME_IS_ACTIVATED ?>"
                        value="1"
                        <?php if ($user->isActivated()): ?>
                            checked
                        <?php endif; ?>

                        <?php if ($user->isActivated()): ?>
                            disabled
                        <?php endif; ?>
                    >
                </label>
            </form>
        </td>

        <td class="delete">
            <form class="delete" action="<?=  \Auth\App\Action\Api\UserDelete::getUrl() ?>" method="post">
                <!-- fixme использовать константу для name ok-->
                <input type="hidden" name="<?= \Auth\App\Action\Admin\Users::POST_NAME_ID ?>" value="<?= $user->getId() ?>">
                <button type="submit">Удалить</button>
            </form>
        </td>
    </tr>
<?php endforeach; ?>
<script>
    $('table.users').on('submit', '.delete', (e) =>
    {
        let $form = $(e.currentTarget);

        let $tr = $form.parents('tr');

        let user_login = $tr.find('.login').text();

        if (!confirm(`Удалить пользователя ${user_login}?`)) {
            return false;
        }
        // todo подтверждать удаление с помощью confirm('Удалить пользователя <user name>') ok

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
