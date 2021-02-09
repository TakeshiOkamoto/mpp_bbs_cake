<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\LangType[]|\Cake\Collection\CollectionInterface $langTypes
 */

 $this->assign('title', __('カテゴリ - 管理画面'));
?>
<p></p>
<h1><?= __('カテゴリ') ?></h1>
<p></p>
<?php
    // 検索
    echo $this->Form->create(null, ['type' =>'get',
                                    'url' => $this->Url->build('/lang-types', true),
                                    'novalidate' => true // ※HTML5のValidation機能
                                   ]);                                   
      echo '<div class="input-group">';
        echo $this->Form->control('name', ['type' =>'search' ,'label' => false , 
                                           'class' => 'form-control', 
                                           'placeholder' => __('検索したい名前を入力'), 'value' => $name]);
        echo '<span class="input-group-btn">';
          echo $this->Form->button(__('検索'), ['class' => 'btn btn-outline-info']);
        echo '</span>';
      echo '</div>';      
    echo  $this->Form->end(); 
?>

<p></p>
<table class="table table-hover">
  <thead class="thead-default">
    <tr>
      <th><?= __('名前') ?></th>
      <th class="pc"><?= __('キーワード') ?></th>
      <th class="pc"><?= __('ソート') ?></th>
      <th class="pc"><?= __('表示') ?></th>   
      <th></th>      
    </tr>
  </thead>
  <tbody class="thead-default">
      <?php foreach ($langTypes as $langType): ?>
        <tr>
            <td><a href="<?= $this->Url->build('/lang-types/view/' . $langType->id, true) ?>"><?= h($langType->name) ?></a></td>
            <td class="pc"><?= h($langType->keywords) ?></td>
            <td class="pc" style="width:80px;"><?= h($langType->sort) ?></td>
            <td class="pc" style="width:70px;"><?= ($langType->show == 1)? 1: 0; ?></td> 
            <td style="width:170px;">
              <a href="<?= $this->Url->build('/lang-types/edit/' . $langType->id, true) ?>" class="btn btn-primary"><?= __('編集') ?></a>
              &nbsp;&nbsp;
              <!-- 
                //  Ajax(delete) 
              -->
              <a href="#" onclick="ajax_delete('<?= __('「{0}」を削除します。よろしいですか？', h($langType->name)) ?>','<?= $this->Url->build('/lang-types/delete/' . $langType->id, true) ?>','<?= $this->Url->build('/lang-types', true) ?>');return false;" class="btn btn-danger"><?= __('削除') ?></a>
              <!--
                // 標準機能(POST)を使用する場合
                <?= $this->Form->postLink(__('削除'), ['action' => 'delete', $langType->id], ['confirm' => __('{0}を削除します。よろしいですか？', $langType->name), 'class' => 'btn btn-danger']) ?>
              -->
            </td>
        </tr>
      <?php endforeach; ?>
  </tbody>
</table>
<p></p>

<?php if ($this->Paginator->param('count') > 0) {?>
<nav>
  <ul class="pagination">
    <?= $this->Paginator->prev('‹') ?>
    <?= $this->Paginator->numbers() ?>
    <?= $this->Paginator->next('›') ?>
  </ul>
  <p><?= $this->Paginator->counter(['format' => __('全{{count}}件中 {{start}} - {{end}}件のデータが表示されています。')]) ?></p>
</nav>
<?php }else{ ?>
  <p>データがありません。</p>
<?php } ?>
    
<p></p>
<a href="<?= $this->Url->build('/lang-types/add', true) ?>" class="btn btn-primary">カテゴリの新規登録</a>
<p><br></p>  