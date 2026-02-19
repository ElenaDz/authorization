<?php
use Auth\App\Action\RecoveryPass;

/**
 * @var $email_error
 */
?>

<div>
    <h1>Ошибка</h1>
    <div>
        <span>
            Указанный адрес электронной почты <?= $email_error ?> не найден или введён неверно.
            Пожалуйста,
            <a href="<?=  RecoveryPass::getUrl([RecoveryPass::POST_NAME_EMAIL_ERROR => $email_error]); ?>">
                введите правильный адрес
            </a>.
        </span>
    </div>
</div>