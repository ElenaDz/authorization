<?php

/**
 * @var $q
 */

?>
<div class="search">
    <form action="<?= \Auth\App\Action\Admin\Users::getUrl()?>" method="post">
        <label for="q">
            Поиск по e-mail:
        </label>
        <!-- fixme внимательно посмотри что именно ты обернула в if, если удалить if то будет ошибка html ok -->
        <input
            type="text"
            id="q"
            name="<?= \Auth\App\Action\Admin\Users::POST_NAME_Q ?>"
            value="<?= htmlspecialchars($q ?? '') ?>"
        >
    </form>
</div>

<script>
    $('.search input').on('keydown', (e) =>
    {

		// todo !!!! ВНИМАНИЕ !!!  отключаю js до тех пор пока не сделаешь полностью работающую версию без js
		// return  true;

        if (e.key !== 'Enter') return ;

        let $input = $(e.currentTarget);
        let $form = $input.parents('form');
        let q = $input.val();

        $.ajax({
            url: $form.attr("action"),
            method: 'POST',
            data: { q: q},
            success: function(response) {
				// todo замена не всего body а таблицы и кнопки Еще (должна быть обертка вокруг них)
                let parser = new DOMParser();

                let doc = parser.parseFromString(response, 'text/html');

                let tbody = $(doc).find('.users tbody').html();

                let new_user_id_first = $(doc)
                    .find('.wrap_show_more')
                    .data('<?= \Auth\App\Action\Admin\Users::GET_NAME_USER_ID_FIRST ?>');


                $('.wrap_show_more').data('<?= \Auth\App\Action\Admin\Users::GET_NAME_USER_ID_FIRST ?>', new_user_id_first);

                $('.users tbody').html(tbody);

            },
            error: function() {
				// todo используй библиотеку
                alert('Ошибка');
            }
        });

        return false;
    });
</script>