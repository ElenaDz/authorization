<?php

namespace Auth\App\Action;

use Auth\App\Model\Users;

class DeleteNotActivatedUsers extends _Base
{
    public function __invoke()
    {

        $user_not_activated = Users::getNotActivated();
        if ($user_not_activated)
        {
            Users::deleteById($user_not_activated->getId());
        }
    }
}