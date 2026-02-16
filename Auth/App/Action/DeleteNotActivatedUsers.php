<?php

namespace Auth\App\Action;

use Auth\App\Model\Users;
use DateTime;

class DeleteNotActivatedUsers extends _Base
{
    public function __invoke()
    {
        $users = Users::getNotActivated();

        foreach ($users as $user)
        {
            $created_at = $user->getCreatedAt();

            $created_at = new DateTime($created_at);

            $now = new DateTime();

            $interval = $created_at->diff($now);

            if ($interval->days <= 7) {
                return;
            }

            $user->delete();
        }
    }
}