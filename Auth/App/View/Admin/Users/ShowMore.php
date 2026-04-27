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

<script>
    $('.table-wrapper').on('click', '.show_more', (e) =>
    {
        let $btn = $(e.currentTarget);

        let $form = $btn.parents('form');

        let form_serialize = $form.serialize();

        $btn.addClass('loading');

        $btn.prop('disabled', true)

        $.ajax({
            url: $form.attr("action"),
            method: 'GET',
            data:  form_serialize,
            success: function(response)
            {
                let parser = new DOMParser();

                let doc = parser.parseFromString(response, 'text/html');

                let tbody = $(doc).find('.users tbody').html();

                let $wrap_show_more =  $(doc).find('.wrap_show_more');

                $('.wrap_show_more').replaceWith($wrap_show_more);

                $('.users tbody').append(tbody);
            },
            error: function(jqXHR)
            {
                showErrorAjix(jqXHR);
            },
            complete: () =>
            {
                $btn.prop('disabled', false);
                $btn.removeClass('loading');
            }
        });

        return false;
    });
</script>