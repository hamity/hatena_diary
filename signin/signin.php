<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>はてな匿名ダイアリー</title>
  </head>
  <body>
    <header>
      <img src="./src/image/common/hatelabo.png" alt="Hatelabo::">
      <img src="./src/image/common/anonymous_diary.png" alt="AnonymousDiary">
    </header>
    <div id="user">
      <div id="user-name">
        ようこそhogeさん
      </div>
      <ul id="signin-signup">
        <a href="#" id="signup">ユーザー登録</a>
        <a href="#" id="signin">ログイン</a>
      </ui>
      <div id="user-data-input">
        <form class="input-form" action="./user-check" method="post">
          <input type="text" name="userdata" value="username">
          <input type="password" name="userdata" value="password">
          <input type="checkbox" name="userdata" value="datasave">
          <input type="submit" value="ログイン">
        </form>
      </div>
      ----     はてなIDをお持ちでない方は      ----
    </div>
  </body>
</html>
