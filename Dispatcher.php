<?php
class Dispatcher
{
  public function dispatch()
  {
    echo 'Dispacher Class Call!!<br>';
    $uri = '/hatena_diary/';    //テスト用

    $uri_array = $this->replaceUri($uri);
    $uri_array = $this->replaceUri($_SERVER['REQUEST_URI']);

    $this->getControllerInstance($uri_array);

    // $controller_argument = '';
    // if(strlen($param[0]) === 0 || ctype_digit($param[0]) === true)
    // {
    //   $controller_argument = $param[0];
    //   $param[0] = 'index';
    // }

    // $class_name = ucfirst(strtolower($param[0])).'Controller';
    // require_once(dirname(__FILE__).'/controllers/'.$class_name.'.php');
    // echo $class_name.'<br>';
    // $controller = new $class_name($controller_argument);

    // //URIに呼び出すメソッド名が書かれていない場合はindexAction()を呼び出す
    // $get_name = 'index';
    // $action_argument = '';
    // if(count($param) > 1)
    // {
    //   $get_name = $param[1];
    // }
    // $get_method_name = $get_name.'Action';
    // echo $get_method_name.'<br>';
    // $controller->$get_method_name($action_argument);
  }

  // /hatena_diary以下のURIの配列化. /hatena_diary/hoge/fooの場合、['hoge', 'foo']を返す
  function replaceUri($url)
  {
    $pass = parse_url($url, PHP_URL_PATH);    // TODO これはいるのか？
    $remove_hatena_diary = str_replace('/hatena_diary', '', $pass);
    $url_normalization = ltrim($remove_hatena_diary, '/');
    return explode('/', $url_normalization);
  }

  //引数の0番目の要素を参考にController系のクラスのインスタンスを返す
  function getControllerInstance($uri_array)
  {
    //URIが存在しない場合は、indexControllerを呼ぶ
    if($uri_array[0] == '')
    {
      return $this->loadAndGetControllerInstance('index');
    }

    switch ($uri_array[0]) 
    {
      case 'login':
        return $this->loadAndGetControllerInstance('login');
      
      case 'logout':

        break;
      
      case 'logout':

        break;
    }

    // 数字のみで構成されている場合

    // 英数字のみで構成されている場合

    //それ以外は不正なURL
    
  }

  // 引数の名前がControllerの前についたControllerファイルをロード&インデックスを生成し、インデックスを返すメソッド
  function loadAndGetControllerInstance($controller_first_name)
  {
    $controller_name = ucfirst($controller_first_name) . 'Controller';
    require_once('./controllers/' . $controller_name . '.php');
    return new $controller_name;
  }
}