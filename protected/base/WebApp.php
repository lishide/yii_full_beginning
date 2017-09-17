<?php

class WebApp extends CWebApplication
{
    public function __construct($config = null)
    {
        parent::__construct($config);
        register_shutdown_function(array($this, 'print_err'));
    }

    public function print_err()
    {
        if (YII_ENABLE_ERROR_HANDLER && ($error = error_get_last())) {
            $this->handleError($error['type'], $error['message'], $error['file'], $error['line']);
            die();
        }
    }
}