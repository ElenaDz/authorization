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
                <input
                    type="hidden"
                    name="id"
                    value="<?= $user->getId() ?>"
                >
                <label>
                    <!-- fixme меняем на то что показано в видео и добавляем live шаблоны ok-->
                    <!-- fixme так как наш флаг может только активировать пользователя
                          и не может снять активацию, нужно добавить disabled, если уже активирован ok -->
                    <input
                        type="checkbox"
                        name="activation"
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
                <input type="hidden" name="id" value="<?= $user->getId() ?>">
                <button type="submit">Удалить</button>
            </form>
        </td>

    </tr>

<?php endforeach; ?>