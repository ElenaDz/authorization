<?php

namespace Auth\App\Enum;

// fixme убрать вместо них использовать константы ссылку на которую я дал ниже
/** @link \Auth\App\Action\Reg::POST_NAME_LOGIN */
class Error
{
    const LIST_LOGIN_ERROR = 'logins';
    const LOGIN_ERROR = 'login';
    const LIST_PASS_ERROR = 'passwords';
    const PASS_ERROR = 'password';
    const EMAIL_ERROR = 'email';
}