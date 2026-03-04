<?php
/** @var string $title */
/** @var string $content */

// todo вернуть сюда все что здесь было раньше, все что должно быть на html странице ok
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0 maximum-scale=1.0, user-scalable=no">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap"
          rel="stylesheet">
    <link rel="stylesheet" href="/Auth/assets/css/main.css?v=<?= filemtime(__DIR__ . '/../../../assets/css/main.css')?>">
    <title><?= $title; ?></title>
</head>
<body>
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
</body>
</html>