# ApacheによるRewrite機能

## まず表示する
Apacheの設定ファイルであるhttpd.confを編集する

```js
# vi /etc/conf/httpd.conf
```

```js
<Directory "/var/www/html/hatena_diary">//このフォルダ以下に設定を適用する
  //中略
    RewriteEngine On    //リライト機能を使用する
    RewriteRule ^(.*)$ https://www.google.co.jp/
    //^(.*)$は全てのURLでということ
    // RewriteRule A B でAのURLをBに書き換えるという意味
</Directory>
```

これで、hatena\_diary以下のすべてのURLで googleが表示されるようになった

*注意: vimで編集後、Apacheの再起動*

```js
# systemctl restart httpd
```

*を行う必要あり*

## index.htmlでアクセスされた時に、index.phpに変換する

httpd.confの中の一部を下のように修正

```js
<Directory "/var/www/html/hatena_diary">
  //中略
    RewriteEngine On    //リライト機能を使用する
    RewriteRule ^(.*)/index\.html$ $1/index\.php
</Directory>
```

だが、”/hatena\_diary/index.html”でアクセスしてもしてもNot Foundが返ってくる.

[正規表現サイト](http://regex-testdrive.com/ja/dotest)でテストしてみると、きちんと変換される。

### なぜ" \\ "が必要なのか?

先ほどのhttpd.confには

```js
    RewriteRule ^(.*)/index\.html$ $1/index\.php
```

と書かれており \\ が登場している。

これは.や$や\*など、正規表現の制御文字として使用される用語を正規表現の条件の文字として使用したい場合に、これは正規表現の制御文字としての使用ではないですよということを伝える。

## Logを見て、なぜNot Foundが返ってくるか考える

Apacheのアクセスログである

/var/log/httpd/access\_logをvimで見てみる。

```js
192.168.33.1 - - [17/Jan/2018:14:29:11 +0900] "GET /hatena_diary/index.html HTTP/1.1" 200 - "-" "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_13_2) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/63.0.3239.84 Safari/537.36"
```

と行った感じのものが何十行も書いてある。これの見方は以下参考

[http://web.just4fun.biz/?Apache/access\_log%E3%81%AE%E8%A6%8B%E6%96%B9](http://web.just4fun.biz/?Apache/access_log%E3%81%AE%E8%A6%8B%E6%96%B9)

## rewrite時にデバッグする

だいたい以下を参考にすれば良い

[https://www.ideaxidea.com/archives/2010/04/mod\_rewrite\_tips.html](https://www.ideaxidea.com/archives/2010/04/mod_rewrite_tips.html)

重要なことはApacheの設定に以下を追加するということ

```js
RewriteLog /tmp/rewrite.log
RewriteLogLevel 9
```

## でも結局
アクセスしても/tmp/rewrite.logにlogファイルできてない……。
と思ったら、/var/www/html/hatena_diaryに.htaccessがあった！
.htaccessの方がリライトルールとしては上位にくるため、httpd.confの方の設定が反映されなかったのかーと思ったけどやっぱり変わらない。
systemctl restart httpdすべきだったのかな？と思いやろうとすると

```js
Job for httpd.service failed because the control process exited with error code. See "systemctl status httpd.service" and "journalctl -xe" for details.
```

と表示された。試しに、systemctl status httpd.serviceと打ってみると

```js
● httpd.service - The Apache HTTP Server
   Loaded: loaded (/usr/lib/systemd/system/httpd.service; enabled; vendor preset: disabled)
   Active: failed (Result: exit-code) since Sat 2018-01-20 00:49:06 JST; 21s ago
   
   Jan 20 00:49:06 localhost.localdomain httpd[12133]: [Sat Jan 20 00:49:06.896754 2018] [so:warn] [pid 12133] AH01574: module rewrite_module is already loaded, skipping
Jan 20 00:49:06 localhost.localdomain httpd[12133]: [Sat Jan 20 00:49:06.913600 2018] [so:warn] [pid 12133] AH01574: module rewrite_module is already loaded, skipping
Jan 20 00:49:06 localhost.localdomain httpd[12133]: AH00526: Syntax error on line 165 of /etc/httpd/conf/httpd.conf:
Jan 20 00:49:06 localhost.localdomain httpd[12133]: Invalid command 'RewriteLog', perhaps misspelled or defined by a module not included in the server configuration
Jan 20 00:49:06 localhost.localdomain systemd[1]: httpd.service: main process exited, code=exited, status=1/FAILURE
Jan 20 00:49:06 localhost.localdomain kill[12135]: kill: cannot find process ""
Jan 20 00:49:06 localhost.localdomain systemd[1]: httpd.service: control process exited, code=exited status=1
Jan 20 00:49:06 localhost.localdomain systemd[1]: Failed to start The Apache HTTP Server.
Jan 20 00:49:06 localhost.localdomain systemd[1]: Unit httpd.service entered failed state.
Jan 20 00:49:06 localhost.localdomain systemd[1]: httpd.service failed.
```

落ちてた。httpd.confでシンタックスエラー。

## vimで行番号を表示する

エラーに行が書かれているので修正は簡単ね！

と思ったら、vimって行番号表示してないじゃん！と思ったので、表示する。

参考: <https://qiita.com/spyder1211/items/c5dd49a3a799bd146599>

しかし、なぜエラーが出ているのかわからないため、

```js
RewriteLog /tmp/rewrite.log
RewriteLogLevel 9
```

の部分を消した。RewriteLogの描き場所がよくなかったのか。

\#\# RewriteLogはもう無くなった

その後、RewriteLogを色々な箇所で試してみるもうまくいかず。

調べてみると、RewriteLogやRewriteLogLevelはApacheのver2.4で無くなってた。

```js
[root@localhost ~]# httpd -v
Server version: Apache/2.4.6 (CentOS)
Server built:   Oct 19 2017 20:39:16
```

私の環境はver2.4.6だったので、使用できずエラーが出てたわけである。

(参考URL: <https://blog.cles.jp/item/8180>, [http://httpd.apache.org/docs/current/mod/mod\_rewrite.html](http://httpd.apache.org/docs/current/mod/mod_rewrite.html))

ゆえに、以下のように変更する。

```js
<Directory "/var/www/html/hatena_diary">
  //中略
    LogLevel info rewrite:trace8
</Directory>
```

そしたら、エラー出ずにApacheを再起動できた。

Access\_Logを見てみる

```js
[root@localhost log]# sudo tail /var/log/httpd/access_log
// 中略
192.168.33.1 - - [21/Jan/2018:18:52:20 +0900] "GET /hatena_diary/index.html HTTP/1.1" 404 221 "-" "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_13_2) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/63.0.3239.132 Safari/537.36"
```

若干、実際に表示した時間とここに書かれている時間は異なっている。

また、Access\_Logではどのような変換が行われたのかわからないため、error\_logも見てみる

```js
[root@localhost log]# vi /var/log/httpd/error_log
[Sun Jan 21 19:05:34.194336 2018] [rewrite:trace3] [pid 12472] mod_rewrite.c(470): [client 192.168.33.1:51297] 192.168.33.1 - - [192.168.33.10/sid#7f9c3e942310][rid#7f9c3ebc1fc0/initial] [perdir /var/www/html/hatena_diary/] strip per-dir prefix: /var/www/html/hatena_diary/index.html -> index.html
[Sun Jan 21 19:05:34.194365 2018] [rewrite:trace3] [pid 12472] mod_rewrite.c(470): [client 192.168.33.1:51297] 192.168.33.1 - - [192.168.33.10/sid#7f9c3e942310][rid#7f9c3ebc1fc0/initial] [perdir /var/www/html/hatena_diary/] applying pattern '^(.*)/index\\.html$' to uri 'index.html'
[Sun Jan 21 19:05:34.194372 2018] [rewrite:trace1] [pid 12472] mod_rewrite.c(470): [client 192.168.33.1:51297] 192.168.33.1 - - [192.168.33.10/sid#7f9c3e942310][rid#7f9c3ebc1fc0/initial] [perdir /var/www/html/hatena_diary/] pass through /var/www/html/hatena_diary/index.html
```

うーんわからん。

## スモールステップで原因を探る

Apacheは正常に動いているようなので、やっぱり正規表現周りがダメなんだろうかと思うので、そこをスモールステップで調査してみる。

まず、

```js
<Directory "/var/www/html/hatena_diary">
        RewriteEngine On
        RewriteRule ^/index\.html$ https://www.google.co.jp/ [L]
        LogLevel alert rewrite:trace8
</Directory>
```

を試してみる。

http://192.168.33.10/hatena\_diary/index.html

でアクセスするとエラー出る。ということで、

```js
<Directory "/var/www/html/hatena_diary">
        RewriteEngine On
        RewriteRule ^index\.html$ https://www.google.co.jp/ [L]
        LogLevel alert rewrite:trace8
</Directory>
```

としたところ、Googleに接続された。これにより、RewriteRuleのルールとして判定される部分は**Directory + /以外**の部分であることがわかった。hatena\_diary/hogehoge/index.htmlでも同様に判定されるように修正をした。それが下のコードとなる。

```js
<Directory "/var/www/html/hatena_diary">
        RewriteEngine On
        RewriteRule ^(.*)index\.html$ https://www.google.co.jp/ [L]
        LogLevel alert rewrite:trace8
</Directory>
```

ということは、出力されるURL部分は

Directory + / = $1

として書けば良いことがわかる。やってみる。

```js
<Directory "/var/www/html/hatena_diary">
        RewriteEngine On
        RewriteRule ^(.*)index\.html$ $1index.php [L]
        LogLevel alert rewrite:trace8
</Directory>
```

http://192.168.33.10/hatena\_diary/index.htmlで表示されるようになった！やったー！！









