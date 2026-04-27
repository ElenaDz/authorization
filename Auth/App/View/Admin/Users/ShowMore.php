<?php
use Auth\App\Entity\User;

/**
 * @var User[] $users
 * @var $q
 * @var $user_id_first
 * @var int $limit
 */

?>
<!-- todo здесь нужен не display: none а if ом обернуть весь блок ok-->
<!-- fixme проверять нужно $user_id_first а $has_users_more вообще не нужен ok-->
<!-- todo вынести в отдельный блок ok -->
<!-- fixme не вижу где есть поисковый запрос ok -->

<?php if ($user_id_first): ?>

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
<?php endif; ?>

<script>

    $('.table-wrapper').on('click', '.show_more', (e) =>
    {
        let btn = $(e.currentTarget);

        let $form = btn.parents('form');

        let  $form_serialize =  $form.serialize();

        let user_id_first = $form.data('<?= \Auth\App\Action\Admin\Users::GET_NAME_USER_ID_FIRST ?>');

        $form.find('input[name="<?= \Auth\App\Action\Admin\Users::GET_NAME_USER_ID_FIRST ?>"]').val(user_id_first);

        // fixme так не пойдет, поисковый запрос должен быть в форме даже без js ok

        btn.addClass('loading');

        btn.prop('disabled', true)

        // fixme js нет необходимости знать детали того что именно передавать он должен передавать все что есть в форме ok
        $.ajax({
            url: $form.attr("action"),
            method: 'GET',
            data:  $form_serialize,
            success: function(response)
            {
                let parser = new DOMParser();

                let doc = parser.parseFromString(response, 'text/html');

                let tbody = $(doc).find('.users tbody').html();

                let $wrap_show_more =  $(doc).find('.wrap_show_more');

                $('.wrap_show_more').replaceWith($wrap_show_more);

                // fixme это хрупкий способ инициализации который может привести к повторному вешанью событий,
                //   необходимо использовать подход такой же как с кнопками удалить ok

                $('.users tbody').append(tbody);
            },
            error: function(jqXHR, textStatus, errorThrown) {

                // todo текст ошибки здесь должен быть тот который написан на странице ошибки (не получилось)
                butterup.toast({
                    title: 'Не получилось показать ещё',
                    message: jqXHR.responseText,
                    location: 'bottom-right'
                })
            },
            complete: () =>
            {
                btn.prop('disabled', false);
                btn.removeClass('loading');
            }
        });

        return false;
    });
</script>

</div>