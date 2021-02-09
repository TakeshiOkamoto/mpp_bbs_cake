<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Questions[]|\Cake\Collection\CollectionInterface $questions
 */

 $this->assign('title', h($lang_types_item->name));
 $this->assign('keywords', h($lang_types_item->keywords));
 $this->assign('description', h($lang_types_item->description));
?>
<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="<?= $this->Url->build('/' , true)  ?>"><?= __('トップ') ?></a></li>
    <li class="breadcrumb-item active"><?= h($lang_types_item->name) ?></li> 
  </ol> 
</nav>    
<p></p>
<h1><?= h($lang_types_item->name) ?></h1>
<p></p>
<?php
    // 検索
    echo $this->Form->create(null, ['type' =>'get',
                                    'url' => $this->Url->build('/questions', true),
                                    'novalidate' => true, // ※HTML5のValidation機能
                                    'class' => 'mb-5'
                                   ]);    
        echo $this->Form->control('lang_id', ['type' =>'hidden' , 'value' => $lang_types_item->id]); 
        echo $this->Form->control('title',   ['type' =>'search' ,
                                              'label' => ['text' => __('タイトル'), 'class' => 'col-sm-2 col-form-label'], 
                                              'class' => 'form-control col-sm-10', 
                                              'placeholder' => __('キーワードを入力 ※複数可'), 'value' => $title]);    
        echo $this->Form->control('body',    ['type' =>'search' ,
                                              'label' => ['text' => __('本文'), 'class' => 'col-sm-2 col-form-label'], 
                                              'class' => 'form-control col-sm-10', 
                                              'placeholder' => __('キーワードを入力 ※複数可'), 'value' => $body]);                                            
        echo $this->Form->button(__('検索'), ['class' => 'btn btn-outline-primary']);  
    echo  $this->Form->end(); 
?>

<p></p>
<a href="<?= $this->Url->build('/questions/add?lang_id=' . $lang_types_item->id, true) ?>" class="btn btn-primary"><?= __('質問を新規作成する') ?></a>
<p></p>  

<?php if ($this->Paginator->param('count') > 0) {?>
<nav>
  <ul class="pagination">
    <?= $this->Paginator->prev('‹') ?>
    <?= $this->Paginator->numbers() ?>
    <?= $this->Paginator->next('›') ?>
  </ul>
  <p><?= $this->Paginator->counter(['format' => __('全{{count}}件中 {{start}} - {{end}}件の質問が表示されています。')]) ?></p>
</nav>
<?php }else{ ?>
  <p><?= __('質問がありません。') ?></p>
<?php } ?>

<p></p>
<table class="table table-hover">
  <thead class="thead-default">
    <tr>
      <th class="text-center" style="width: 65px;"><?= __('状態') ?></th>    
      <th><?= __('タイトル') ?></th>
      <th class="pc" style="width:120px;"><?= __('更新日時') ?></th>
      <th class="text-center pc" style="width: 80px;"><?= __('件数') ?></th>
      <th class="text-center pc" style="width: 90px;"><?= __('閲覧数') ?></th>
      <?php if ($session->check('user.name')){ ?>
        <th style="width: 110px;"></th>
      <?php } ?>
    </tr>
  </thead>
  <tbody>
    <?php 
      // ヘルパーメソッド
      // ※本来は1つのSQLにすべきです(笑)
      function get_answers_data($objs, $id){
        $result = []; 
        foreach($objs as $obj){
          if ($obj['id'] == $id){
            if (isset($obj['cnt'])){
              $result['cnt'] = $obj['cnt'];
            }else{
              $result['cnt'] =  "破損";
            }  
            $result['name1'] = $obj['name1']; 
            $result['name2'] = $obj['name2'];
            return $result; 
          }  
        }
        return $result;
      }
    ?>
    <?php foreach ($items as $item){ ?>
      <?php $data = get_answers_data($db_data, $item->id); ?>
      <tr> 
        <?php if ($item->resolved == 1){ ?>
          <td><span class="badge badge-success"><?= __('解決') ?></span></td>
        <?php }else{ ?>
          <td></td>
        <?php } ?>
        
        <td>
          <div>
            <div><a href="<?= $this->Url->build('/answers?question_id=' . $item->id, true) ?>"><?= h($item->title) ?></a></div> 
            <?php if ($data['cnt'] == 1){ ?>
              <div class="text-muted" style="font-size:90%"><?= __('質問者') ?> <?= h($data['name1']) ?></div>
            <?php }else{ ?>
              <div class="text-muted" style="font-size:90%"><?= __('質問者') ?> <?= h($data['name1']) ?> <?= __('最終発言者') ?> <?= h($data['name2']) ?></div>
            <?php } ?>
          </div>
        </td>
        
        <td class="pc"><?= h($item->updated_at) ?></td>
        <td class="text-center pc"><?= number_format($data["cnt"]) ?></td>
        <td class="text-center pc"><?= number_format($item->pv) ?></td> 
        <?php if ($session->check('user.name')){ ?>
          <td><a href="#" onclick="ajax_delete('<?= __('「{0}」を削除します。よろしいですか？', h($item->title)) ?>','<?= $this->Url->build('/questions/delete/' . $item->id, true) ?>','<?= $this->Url->build('/questions?lang_id=' . $lang_types_item->id, true) ?>');return false;" class="btn btn-danger"><?= __('削除') ?></a></td>
        <?php } ?>
      </tr>
    <?php } ?>
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
  <p><?= $this->Paginator->counter(['format' => __('全{{count}}件中 {{start}} - {{end}}件の質問が表示されています。')]) ?></p>
</nav>
<?php }else{ ?>
  <p><?= __('質問がありません。') ?></p>
<?php } ?>

<p></p>
<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="<?= $this->Url->build('/' , true)  ?>"><?= __('トップ') ?></a></li>
    <li class="breadcrumb-item active"><?= h($lang_types_item->name) ?></li> 
  </ol> 
</nav>
<p></p>