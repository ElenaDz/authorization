<?php
use Auth\App\Action\RecoveryPass;
?>

<div>
    <h1>Ошибка</h1>
    <div>
        <span>
            Указанный адрес электронной почты не найден или введён неверно.
            Пожалуйста, <a href="<?=  RecoveryPass::getUrl(); ?>">введите правильный адрес</a>.
        </span>
    </div>
</div>