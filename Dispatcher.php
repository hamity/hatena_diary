<?php
class Dispatcher
{
  //アクセス権(publicやprivate)を明示せずに定義した場合はpublicになる
  /*
  const CONTROLLER_NAME = array('index',
  'login',
  'logout');
  */

  public function dispatch()
  {
    $uri = $_SERVER['REQUEST_URI'];
    $uri = '928520';

    $uri_normalization = $this->replaceUri($uri);

    $param = explode('/', $uri_normalization);
    echo $param[0];

    $controller_argument = '';
    if(strlen($param[0]) === 0 || ctype_digit($param[0]) === true)
    {
      $controller_argument = $param[0];
      echo $controller_argument;
      $param[0] = 'index';
    }

    //ユーザー名の場合の処理
    /*
    for($i = 0; $i < count($this->CONTROLLER_NAME); $i++)
    {
      if($CONTROLLER_NAME[$i] === $param[0])
      {
        break;
      }
      $controller_argument = $param[0];
      $param[0] = 'user';
    }
    */

    $class_name = ucfirst(strtolower($param[0])).'Controller';
    require_once(dirname(__FILE__).'/controllers/'.$class_name.'.php');
    echo $class_name.'<br>';
    $controller = new $class_name($controller_argument);

    //URIに呼び出すメソッド名が書かれていない場合はindexAction()を呼び出す
    $get_name = 'index';
    $action_argument = '';
    if(count($param) > 1)
    {
      $get_name = $param[1];
    }
    $get_method_name = $get_name.'Action';
    echo $get_method_name.'<br>';
    $controller->$get_method_name($action_argument);
  }

  function replaceUri($uri)
  {
        // /hatena_diary/だと空白が代入される
        $uri_normalization = ereg_replace('^(/hatena_diary/)','', $uri);
        $uri_normalization = ereg_replace('\.([a-z]+)$', '', $uri_normalization);
        $uri_normalization = ereg_replace('/$', '', $uri_normalization);
        return $uri_normalization;
  }
}