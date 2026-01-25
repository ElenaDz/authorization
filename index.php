<?php
require __DIR__.'/Auth/autoload.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>DriveMusic</title>
</head>
<body>
  <a href="<?= \Auth\App\Action\Logon::getUrl(['param_optional' => 123]); ?>">Вход</a>
</body>
</html>