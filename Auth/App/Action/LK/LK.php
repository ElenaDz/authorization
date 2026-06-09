<?php
declare(strict_types=1);

namespace Auth\App\Action\LK;

use Auth\App\Action\_Base;
use Auth\App\Model\History;
use Auth\App\Service\Auth;
use Auth\Sys\Request;
use Auth\Sys\Views;

class LK extends _Base
{
	public function __invoke()
	{
        if ( ! headers_sent()) {
            if (Request::isDevelopment()) {
                header('Access-Control-Allow-Origin: http://mp3player');
                header('Access-Control-Allow-Credentials: true');
                header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
                header('Access-Control-Allow-Headers: Content-Type, Authorization');
            }
        }

        if ( ! Auth::isAuthorized()) {
			throw new \DomainException('Доступ закрыт, вы не авторизованы', 403);
        }

        $user = Auth::getUser();

        $is_authorized = Auth::isAuthorized();

        echo Views::get(
            __DIR__ . '/../../View/LK.php',
            [
                'user' => $user,
                'is_authorized' => $is_authorized
            ]
        );
	}

	public static function getUrl(array $params = []): string
	{
		return parent::getUrl($params);
	}
}