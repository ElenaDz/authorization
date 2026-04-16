<?php

/**
 * @var $part_email
 */

?>
<div class="search">
    <form action="<?= \Auth\App\Action\Admin\Users::getUrl()?>" method="post">
        <label for="part_email">
            Поиск по e-mail:
        </label>
        <!-- fixme внимательно посмотри что именно ты обернула в if, если удалить if то будет ошибка html -->
        <input
            type="text"
            id="part_email"
            <?php if ( ! empty($part_email)): ?>

                value="<?= $part_email ?>">

            <?php endif; ?>
    </form>
</div>

<script>
    $('.search input').on('keydown', (e) =>
    {
		
		// todo !!!! ВНИМАНИЕ !!!  отключаю js до тех пор пока не сделаешь полностью работающую версию без js
		return  true;

        if (e.key !== 'Enter') return ;

        let $input = $(e.currentTarget);
        let $form = $input.parents('form');
        let part_email = $input.val();

        $.ajax({
            url: $form.attr("action"),
            method: 'POST',
            data: { part_email: part_email, },
            success: function(response) {
				// todo замена не всего body а таблицы и кнопки Еще (должна быть обертка вокруг них)
                $('body').html(response);
            },
            error: function() {
				// todo используй библиотеку
                alert('Ошибка');
            }
        });

        return false;
    });
</script>