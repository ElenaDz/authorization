<?php

namespace Sys;

class Views
{
    public static function get($__file_path, $__data = [])
    {
        extract($__data);

        unset($__data);

        ob_start();

        include $__file_path;

        return ob_get_clean();
    }
}