<?php

/**
 * @var $part_email
 */

?>
<!-- todo вынеси блок поиска в отдельный шаблон в папку Users ok-->
<div class="search">
    <form action="<?= \Auth\App\Action\Admin\Users::getUrl()?>" method="post">
        <label for="part_email">
            Поиск по e-mail:
        </label>
        <input
                type="text"
                id="part_email"
                <?php if ( ! empty($part_email)): ?>

                    value="<?= $part_email ?>">

                <?php endif; ?>
    </form>
</div>

<!-- fixme размести скрипт непосредственно под тем html элементом к которому он относиться ok-->
<script>
    $('.search input').on('keydown', (e) =>
    {
        if (e.key !== 'Enter') return ;

        let $input = $(e.currentTarget);
        let $form = $input.parents('form');
        let part_email = $input.val();

        $.ajax({
            url: $form.attr("action"),
            method: 'POST',
            data: { part_email: part_email, },
            success: function(response) {
                $('body').html(response);
            },
            error: function() {
                alert('Ошибка');
            }
        });

        return false;
    });
</script>