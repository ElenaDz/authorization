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
</head>

<body>
    <!-- todo перенести b_auth_modal ниже чтобы не было проблем с тем что модальное окно не перекрывает содержание страницы -->
    <div class="b_auth_modal"></div>

    <div class="b_auth_btn">

        <?php if ( ! \Auth\App\Service\Auth::isAuthorized()): ?>
            <a href="<?= \Auth\App\Action\Logon::getUrl(); ?>">
                Вход
            </a><br>

        <?php else: ?>

            <b><?= \Auth\App\Service\Auth::getUser()->getLogin(); ?></b>
            ( <?= \Auth\App\Service\Auth::getUser()->getEmail(); ?> )
            <form method="post" action="<?= \Auth\App\Action\Logout::getUrl()?>">
                <button type="submit">Выход</button>
            </form>

        <?php endif; ?>

    </div>

    <br>
    <a href="<?= \Auth\App\Action\TestBox::getUrl(); ?>">
        Тестовая площадка
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
            __DIR__.'/Auth/assets/js/auth.js'
        ],
        __DIR__.'/Auth/assets/js/auth.one_file.js'
    );
    ?>

    <script src="<?= $getUrl(__DIR__ . '/Auth/assets/js/auth.one_file.js'); ?>"></script>
</body>
</html>