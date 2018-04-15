<?php
class IndexController
{
  function __construct()
  {
    echo 'load IndexConroller<br>';
  }

  public function indexAction($argument)
  {
    var_dump($argument);
  }
}
