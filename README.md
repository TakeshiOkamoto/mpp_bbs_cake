# 掲示板システム
  
DEMO    
[https://www.petitmonte.com/cake/mpp_bbs_cake/](https://www.petitmonte.com/cake/mpp_bbs_cake/)  
  
[mpp_bbs_cakeの意味]  
mpp = My Practice Project  
bbs = 掲示板  
cake = CakePHP   
    
## 1. 環境
・CakePHP 3.9系  
・MariaDB 10.2.2以上 (MySQL5.5以上でも可)  
 
## 2. インストール方法
  
### プロジェクトの生成  
```rb
cd 任意のディレクトリ
composer create-project --prefer-dist cakephp/app:3.9.* 任意のプロジェクト名
```
次にココにあるファイルをダウンロードして、プロジェクトに上書きします。

### config/app_local.php
本番モード  
```rb
'debug' => filter_var(env('DEBUG', false), FILTER_VALIDATE_BOOLEAN),
```
データベース
```rb
'Datasources' => [
        'username' => 'ユーザー名',
        'password' => 'パスワード',
        'database' => 'データベース名', 
        'log' => false, 
],
```
logは任意です。SQLログ(logs/queries.log)の出力設定です。

### config/app.php
ロケール/タイムゾーンの設定(アプリ側)
```rb
'App' => [
    'encoding' => env('APP_ENCODING', 'UTF-8'),
    'defaultLocale' => env('APP_DEFAULT_LOCALE', 'ja_JP'),
    'defaultTimezone' => env('APP_DEFAULT_TIMEZONE', 'Asia/Tokyo'),
],
```
タイムゾーンの設定(MySQL/MariaDB側)
```rb
'Datasources' => [
    'default' => [
        'timezone' => 'Asia/Tokyo',
        'quoteIdentifiers' => true,
    ],
],
```
ついでにquoteIdentifiersをtrueにして下さい。  
CakePHPで発行されるSQLのテーブル名、カラム名の前後にバッククォートが付加されます。  
```rb
(例) select `カラム名` from `users`
```  
ここのタイムゾーンの設定がエラーになる場合は[ココ](https://www.petitmonte.com/php/cakephp_project.html#SQLSTATE[HY000])を参照。  
### bin/cake
bin/cakeファイルのパーミッションは実行権限を付与して下さい。(例)700 or 744 or 764など
  
### マイグレーション
```rb
bin/cake migrations migrate
```
### 管理者アカウントの作成
コンソールコマンド(src/Command/HelloCommand.php)を作成していますので、
```rb
bin/cake hello ユーザー名 メールアドレス パスワード
```
で登録可能です。※スペースは半角スペースにして下さい。
```rb
(例)
bin/cake hello admin admin@example.com 12345678
```

### 実行する
```rb
bin/cake server
```
メイン    
[http://localhost:8765/](http://localhost:8765/)   
ログイン   
[http://localhost:8765/admin/login](http://localhost:8765/admin/login) 
  
## 3. CakePHPプロジェクトの各種初期設定
その他は次の記事を参照してください。  
  
[CakePHPプロジェクトの各種初期設定](https://www.petitmonte.com/php/cakephp_project.html)  

## 同梱ファイルのライセンス
Bootstrap v4.3.1 (https://getbootstrap.com/)  
```rb
Copyright 2011-2019 The Bootstrap Authors  
Copyright 2011-2019 Twitter, Inc.
```

