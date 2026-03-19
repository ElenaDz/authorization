<?php
require __DIR__.'/Auth/autoload.php';

$getUrl = function ($file_path)
{
	$_file_path = realpath($file_path);
	if (empty($_file_path)) {
		throw new Exception(
			sprintf(
				'Файл не найден "%s"',
				$file_path
			)
		);
	}

	$url = substr(
		$_file_path,
		strlen(realpath(__DIR__))
	);

	$url = str_replace('\\', '/', $url);

	return $url . '?v=' . (new \DateTime())->setTimestamp(filemtime($_file_path))->format('Y-m-d_H:i:s');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>DriveMusic</title>
    <link rel="stylesheet" href="<?= $getUrl(__DIR__ . '/Auth/assets/css/main.css'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0 maximum-scale=1.0, user-scalable=no">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap"
          rel="stylesheet">
</head>

<body>
    <div class="b_auth_btn" data-auth_btn_open_url="<?= \Auth\App\Action\ChangePass::COOKIE_NAME_AUTH_BTN_OPEN_URL; ?>">

        <?php if ( ! \Auth\App\Service\Auth::isAuthorized()): ?>
            <a class="open" href="<?= \Auth\App\Action\Logon::getUrl(); ?>">
                Вход
            </a><br>

        <?php else: ?>

            <b><?= \Auth\App\Service\Auth::getUser()->getLogin(); ?></b>
            ( <?= \Auth\App\Service\Auth::getUser()->getEmail(); ?> )
            <form method="post" action="<?= \Auth\App\Action\Logout::getUrl()?>">
                <button class="exit" type="submit">Выход</button>
            </form>

        <?php endif; ?>

    </div>

    <br>
    <a href="<?= \Auth\App\Action\TestBox::getUrl(); ?>">
        Тестовая площадка
    </a><br>

    <br>
    <a href="https://www.figma.com/design/Wcdl2WmNjDCYluMBndX2DJ/DriveMusic?node-id=201-1057">
        Шаблон в Figma
    </a>

    <br>
    <h2>Cron</h2>
    <ul>
        <li>
            <a href="/Auth/do_cron.php?job=<?= \Auth\App\Action\DeleteNotActivatedUsers::class ?>">
                Удалить не активированных пользователей
            </a>
        </li>
    </ul>

    <script
        src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"
        integrity="sha512-bLT0Qm9VnAYZDflyKcBaQ2gg0hSYNQrJ8RilYldYQ1FxQYoCLtUjuuRuZo+fjqhx/qtq/1itJ0C2ejDxltZVFg=="
        crossorigin="anonymous"
    ></script>

    <?php
    require __DIR__.'/builder.php';

    builder_assets(
        [
            __DIR__.'/Auth/assets/js/auth.js',
            __DIR__.'/Auth/assets/js/auth_btn.js',
            __DIR__.'/Auth/assets/js/auth_modal.js'
        ],
        __DIR__.'/Auth/assets/js/auth.one_file.js'
    );
    ?>

    <script src="<?= $getUrl(__DIR__ . '/Auth/assets/js/auth.one_file.js'); ?>"></script>

    <script>
        $(function() {
            AuthBtn.create($('.b_auth_btn'));
        });
    </script>
</body>
</html>