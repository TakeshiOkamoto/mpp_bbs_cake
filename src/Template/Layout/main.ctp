<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1">
<meta name="robots" content="noindex, nofollow">
<meta name="keywords" content="<?= $this->fetch('keywords') ?>">
<meta name="description" content="<?= $this->fetch('description') ?>">
<meta name="csrf-token" content="<?= $this->request->getParam('_csrfToken')?>">
<title><?= $this->fetch('title') ?></title>
<link rel="stylesheet" media="all" href="<?= $this->Url->build('/css/bootstrap.min.css', true); ?>">
<link rel="stylesheet" media="all" href="<?= $this->Url->build('/css/terminal.css', true); ?>">
<link href="<?= $this->Url->build('/favicon.ico', true); ?>" type="image/x-icon" rel="icon">
<link href="<?= $this->Url->build('/favicon.ico', true); ?>" type="image/x-icon" rel="shortcut icon">
<script src="<?= $this->Url->build('/js/common.js', true); ?>"></script>
</head>
<body>

<!-- ヘッダ -->
<nav class="navbar navbar-expand-md navbar-light bg-primary">
  <div class="navbar-brand text-white">
    <?= __('掲示板システム') ?>
  </div>
  <?php if ($session->check('user.name')){ ?>
    <ul class="navbar-nav ml-auto">
      <li class="nav-item">
        <a class="nav-link" style="color:#fff;" href="<?= $this->Url->build('/', true); ?>"><?= __('ホーム') ?></a>
      </li>
      <li class="nav-item">
        <a class="nav-link" style="color:#fff;" href="<?= $this->Url->build('/accesses', true); ?>"><?= __('アクセス解析') ?></a>
      </li>
      <li class="nav-item">
        <a class="nav-link" style="color:#fff;" href="<?= $this->Url->build('/lang-types', true); ?>"><?= __('カテゴリ') ?></a>
      </li>
      <li class="nav-item">
        <a class="nav-link" style="color:#fff;" href="<?= $this->Url->build('/admin/logout', true); ?>"><?= __('ログアウト') ?></a>
      </li>
    </ul>  
  <?php } ?>
</nav>

<div class="container">

  <!-- フラッシュ -->
  <?= $this->Flash->render() ?>    
  
  <!-- メイン -->
  <div>
    <?= $this->fetch('content') ?>
  </div>
  
  <!-- フッタ -->
  <nav class="container bg-primary p-2 text-center">
    <div class="text-center text-white">
      <?= __('掲示板システム') ?><br>
      Copyright 2021 Takeshi Okamoto All Rights Reserved.
    </div>
  </nav>   
</div>
</body>
</html>
