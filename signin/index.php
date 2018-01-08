<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>はてらぼ ログイン</title>
  </head>
  <body>
    <header>
      <img src="../src/image/common/hatelabo.png" alt="Hatelabo::">
      <img src="../src/image/common/anonymous_diary.png" alt="AnonymousDiary">
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
        <form class="input-form" action="./user-check" method="post"><br>
          <input type="text" name="userdata" value="username" required><br>
          <input type="password" name="userdata" value="password" required><br>
          <input type="checkbox" name="userdata" value="datasave"><br>
          <input type="submit" value="ログイン">
        </form>
      </div>
      ----     はてなIDをお持ちでない方は      ----<br>
      <button type="button" name="button">新規ユーザー登録</button>

      <div class="attention">
        <b>ログインに関する注意事項</b>

        <ul>
          <li>はてなのアカウントでログインできます。パスワードはSSL通信で送信されます。</li>
          <li>はてラボは *.hatelabo.jp ドメインで運営されており、*.hatena.ne.jp ドメインとは別にログイン状態が管理されています。</li>
          <li>パスワードを忘れた方はパスワードの確認で確認してください。</li>
          <li>はてなのアカウントをお持ちでない方は新規ユーザー登録でユーザー登録を行ってください。</li>
          <li>うまくログインできない方はお問い合わせをご覧いただき、Cookieの設定をご確認ください。</li>
        </ul>
      </div>
      <div class="copyright">
        Copyright (C) 2018 hatena. All Rights Reserved.
      </div>
    </div>
  </body>
</html>
