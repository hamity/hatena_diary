<?php
class LoginController
{
    function __construct()
    {
        echo 'load LoginController<br>';
    }

    public function indexAction($argument)
    {
        echo '<br>';
        var_dump($argument);
    }
}