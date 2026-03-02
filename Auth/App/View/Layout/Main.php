<?php
/** @var string $title */
/** @var string $content */

// todo вернуть сюда все что здесь было раньше, все что должно быть на html странице
?>

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
