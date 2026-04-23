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
        <!-- fixme не подставляется поисковый запрос при отключенном js -->
        <input
            type="text"
            id="q"
            name="<?= \Auth\App\Action\Admin\Users::POST_NAME_Q ?>"
            value="<?= htmlspecialchars($q ?? '') ?>"
        >
    </form>
</div>

<script>
    // fixme поисковый запрос срабатывает только один раз, второй поиск ни чего не меняет на странице
    $('.search input').on('keydown', (e) =>
    {
        if (e.key !== 'Enter') return ;

        let $input = $(e.currentTarget);
        let $form = $input.parents('form');
        let q = $input.val();

		// fixme мы не должны в js уточнять какое именно данные нужно передать, передать нужно все data из формы
        $.ajax({
            url: $form.attr("action"),
            method: 'POST',
            data: { q: q},
            success: function(response)
            {
                let parser = new DOMParser();

                let doc = parser.parseFromString(response, 'text/html');

                let $table_wrapper = $(doc).find('.table-wrapper').html();

                $('.table-wrapper').replaceWith($table_wrapper);
            },
            error: function() {
				// todo используй библиотеку
                alert('Ошибка');
            }
        });

        return false;
    });
</script>