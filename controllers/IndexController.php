<?php
class IndexController{
  function __construct(){
    echo 'Load IndexConroller<br>';
  }

  public function indexAction($argument){
    echo $argument;
    //指定された記事を表示する
    if($argument !== ''){
      //$argumentの記事を表示する
    }
    else{
      //記事一覧を表示
    }
  }
}
