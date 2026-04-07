<?php

/**
 * @var User[] $users
 */

use Auth\App\Entity\User;

?>



<div class="admin-panel">
        <div class="panel-header">
            <span class="total_users"><?= count($users)?> пользователей</span>
            <form action="<?= \Auth\App\Action\DeleteNotActivatedUsers::getUrl() ?>">
                <button class="btn-delete-inactive" type="submit">Удалить неактивированных</button>
            </form>

        </div>

        <div class="table-wrapper">
            <table class="users-table">
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
                <?php foreach ($users as $user) : ?>
                <tr>
                    <td class="date"><?= $user->getCreatedAt() ?></td>
                    <td class="date"><?= $user->getLastLoginAt() ?></td>
                    <td class="email"><?= $user->getEmail() ?></td>
                    <td class="law">
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
                        <form action="<?= \Auth\App\Action\Admin\ActivationUser::getUrl() ?>" method="post">
                            <label>
                                <button type="submit">
                                    <input type="checkbox" name="id"  value="<?= $user->getId() ?>" <?= ! $user->getActivationCode() ? 'checked' : '' ?>>
                                </button>
                            </label>
                        </form>
                    </td>
                    <td class="delete">
                        <form action="<?=  \Auth\App\Action\Admin\DeleteUser::getUrl() ?>" method="post">
                            <input type="hidden" name="id" value="<?= $user->getId() ?>">
                            <button class="btn-delete" type="submit">Удалить</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
</div>