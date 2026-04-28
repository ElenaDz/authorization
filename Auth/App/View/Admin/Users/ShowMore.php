<?php
use Auth\App\Entity\User;

/**
 * @var User[] $users
 * @var $q
 * @var $user_id_first
 * @var int $limit
 */

if (empty($user_id_first)) return;
?>

<form
    class="wrap_show_more"
    data-<?= \Auth\App\Action\Admin\Users::GET_NAME_USER_ID_FIRST ?>="<?= $user_id_first?>"
    action="<?= \Auth\App\Action\Admin\Users::getUrl() ?>"
    method="get"
>
    <input type="hidden" name="action" value="<?= \Auth\App\Action\Admin\Users::class; ?>">
    <input type="hidden" name="<?= \Auth\App\Action\Admin\Users::GET_NAME_USER_ID_FIRST ?>" value="<?= $user_id_first?>">
    <input type="hidden" name="<?= \Auth\App\Action\Admin\Users::GET_NAME_Q ?>" value="<?= $q?>">
    <button type="submit" class="show_more">
        <span class="more">
            Показать ещё
        </span>
        <span class="inner_loading">
            Загрузка...
        </span>
    </button>
</form>

