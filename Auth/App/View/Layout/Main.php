<?php
/** @var string $title */
/** @var string $content */
?>

    <!-- fixme здесь модального окна быть не должно здесь только форма авторизации, модальное окно находиться там же
          где auth btn в нашем случае этот index.php ok -->
    <div
        id="<?= $id = uniqid('auth_'); ?>"
        class="b_auth"
    >
        <div class="inner_authentication">
            <?= $content; ?>
        </div>
    </div>

    <script>
        new Auth($('#'+'<?= $id ?>'));
    </script>
