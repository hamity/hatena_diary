<?php
class Dispatcher{
  function __construct(){
    // echo 'Load IndexConroller';
  }

  public function dispatch(){
    $uri = $_SERVER['REQUEST_URI'];

    // /hatena_diary/だと空白が代入される
    $uri_normalization = ereg_replace('^(/hatena_diary/)','', $_SERVER['REQUEST_URI']);
    $uri_normalization = ereg_replace('\.([a-z]+)$', '', $uri_normalization);
    $uri_normalization = ereg_replace('/$', '', $uri_normalization);

    $param = explode('/', $uri_normalization);
    if(strlen($param[0]) === 0){
      $param[0] = 'index';
    }

    $class_name = ucfirst(strtolower($param[0])).'Controller';
    require_once(dirname(__FILE__).'/controllers/'.$class_name.'.php');
    echo $class_name.'<br>';
    $controller = new $class_name();

    //URIに呼び出すメソッド名が書かれていない場合はindex_action()を呼び出す
    $get_name = 'index';
    $argumet = '';
    if(count($param) > 1){
      if(ctype_digit($param[1]) === true){
        $argument = $param[1];
      }else{
        $get_name = $param[1];
      }
    }
    $get_method_name = $get_name.'Action';
    echo $get_method_name.'<br>';
    $controller->$get_method_name($argument);
  }
}
