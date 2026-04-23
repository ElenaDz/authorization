<?php
use Auth\App\Entity\User;

/**
 * @var User[] $users
 */

// todo есть пользователй нет, нужно писать слово "Пусто", чтобы было все ясно ok
?>
<?php foreach ($users as $user) : ?>

    <tr data-user_id="<?= $user->getId() ?>">

        <td class="date"><?= $user->getId() ?></td>
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
                <input
                    type="hidden"
                    name="<?= \Auth\App\Action\Api\UserActivation::POST_NAME_ID ?>"
                    value="<?= $user->getId() ?>"
                >
                <label>
                    <input
                        type="checkbox"
                        name="<?= \Auth\App\Action\Api\UserActivation::POST_NAME_ACTIVATION ?>"
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
                <input
                    type="hidden"
                    name="<?= \Auth\App\Action\Api\UserDelete::POST_NAME_ID ?>"
                    value="<?= $user->getId() ?>"
                >
                <button type="submit">Удалить</button>
            </form>
        </td>
    </tr>

<?php endforeach; ?>

