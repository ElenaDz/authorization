<?php

/**
 * @var $q
 */

?>
<div class="search">
    <form action="<?= \Auth\App\Action\Admin\Users::getUrl()?>" method="post">
        <label for="q">
            Поиск
        </label>
        <input
            type="search"
            id="q"
            name="<?= \Auth\App\Action\Admin\Users::POST_NAME_Q ?>"
            value="<?= htmlspecialchars($q ?? '') ?>"
            placeholder="email"
        >
    </form>
</div>

<script>
    $('.search input').on('keydown', (e) =>
    {
        if (e.key !== 'Enter') return ;

        let $input = $(e.currentTarget);
        let $form = $input.parents('form');

        $.ajax({
            url: $form.attr("action"),
            method: 'POST',
            data: $form.serialize(),
            success: function(response)
            {
                let parser = new DOMParser();

                let doc = parser.parseFromString(response, 'text/html');

                let $table_wrapper = $(doc).find('.table-wrapper').html();

                $('.table-wrapper').html($table_wrapper);
            },
            error: function(jqXHR)
            {
                showErrorAjix(jqXHR);
            }
        });

        return false;
    });
</script>