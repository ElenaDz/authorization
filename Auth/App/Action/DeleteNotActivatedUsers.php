<?php

namespace Auth\App\Action;

use Auth\App\Model\Users;
use DateTime;

class DeleteNotActivatedUsers extends _Base
{
    public function __invoke()
    {
        $users = Users::getNotActivated();

		$count = count($users);

	    $count_deleted = 0;

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

	        $count_deleted ++;

        }

	    $is_cron_run = php_sapi_name() === 'cli';

		if ( ! $is_cron_run) {
			echo sprintf(
				"Всего не активированных пользователей: <b>%s</b><br>".
				"Из них удалено: <b>%s</b>",
				$count,
				$count_deleted
			);

		}
    }
}