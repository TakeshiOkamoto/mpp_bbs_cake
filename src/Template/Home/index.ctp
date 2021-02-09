<?php
  $this->assign('title', __('掲示板システム'));
  $this->assign('keywords', __('キーワード'));
  $this->assign('description', __('説明'));
?>
<p></p>
<table class="table table-hover">
  <thead class="thead-default">
    <tr>
        <th><?= __('カテゴリ') ?></th>    
        <th class="text-center pc"><?= __('質問数') ?></th>
        <th class="text-center pc"><?= __('コメント数') ?></th>
        <th class="text-center pc"><?= __('回答率') ?></th> 
        <th class="text-center pc"><?= __('解決率') ?></th>  
        <th class="text-center pc"><?= __('閲覧数') ?></th>  
    </tr>  
  </thead>
  
  <tbody>
     <?php foreach ($items as $index => $item) { ?>
       <?php if ($item->show == 1) { ?>
       <tr>
         <td><a href="<?= $this->Url->build('/questions?lang_id=' . $item->id , true)  ?>"><?= h($item->name) ?></a></td>
         <td class="text-center pc"><?= number_format($counts[$index]['A']) ?></td>
         <td class="text-center pc"><?= number_format($counts[$index]['B']) ?></td>
         
         <?php if ($counts[$index]['A'] == 0) { ?>
           <td class="text-center pc">0</td>
         <?php }else{ ?>
           <td class="text-center pc"><?= round((($counts[$index]['A'] - $counts[$index]['C']) * 1.0 / $counts[$index]['A'] * 1.0)  * 100, 2) ?>%</td>
         <?php } ?>
         
         <?php if ($counts[$index]['D'] == 0) { ?>
           <td class="text-center pc">0</td>
         <?php }else{ ?>
           <td class="text-center pc"><?= round(($counts[$index]['D'] * 1.0 / $counts[$index]['A'] * 1.0) * 100, 2)?>%</td>
         <?php } ?>         
         
         <td class="text-center pc"><?= number_format($counts[$index]['E']) ?></td>
       </tr>
       <?php } ?>
     <?php } ?>
  </tbody>
</table>
<p></p>