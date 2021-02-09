<?php
  $this->assign('title', __('アクセス解析 - 管理画面'));
?>
<p></p>
<h1><?= __('アクセス解析') ?></h1>
<p></p>

<h2><?= __('日毎(1か月分)') ?></h2>
<p></p>

<table class="table table-hover">
  <thead class="thead-default">
    <tr>
      <th class="text-center"><?= __('年') ?></th>
      <th class="text-center"><?= __('月') ?></th>
      <th class="text-center"><?= __('日') ?></th>
      <th class="text-center"><?= __('PV') ?></th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($one_month_ago as $item){ ?>
    <tr>
      <td class="text-center"><?= $item['yyyy'] ?></td>
      <td class="text-center"><?= $item['mm'] ?></td>
      <?php if ($item['week'] == 0 || $item['week'] == 6) { ?>
        <td class="text-center text-danger"><?= $item['dd'] ?></td>
      <?php }else{ ?>
        <td class="text-center"><?= $item['dd'] ?></td>
      <?php } ?>
      <td class="text-center"><?= $item['pv'] ?></td>
    </tr>  
    <?php } ?> 
  </tbody>
</table>
<p></p>

<h2><?= __('月毎(前年以降)') ?></h2>
<p></p>
<table class="table table-hover">
  <thead class="thead-default">
    <tr>
      <th class="text-center"><?= __('年') ?></th>
      <th class="text-center"><?= __('月') ?></th>
      <th class="text-center"><?= __('1日平均PV') ?></th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($one_year_ago as $item) { ?>
    <tr>
      <td class="text-center"><?= $item['yyyy'] ?></td>
      <td class="text-center"><?= $item['mm'] ?></td>
      <td class="text-center"><?= $item['pv'] ?></td>
    </tr>  
    <?php }  ?>
  </tbody>
</table>
<p><br></p>