<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Answers[]|\Cake\Collection\CollectionInterface $answers
 */

 $this->assign('title', h($question->title . ' - ' . $lang_name));
?>
<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="<?= $this->Url->build('/' , true)  ?>"><?= __('トップ') ?></a></li> 
    <li class="breadcrumb-item"><a href="<?= $this->Url->build('/questions?lang_id=' . $lang_id , true)  ?>"><?= h($lang_name) ?></a></li> 
    <li class="breadcrumb-item active"><?= h($question->title) ?></li>     
  </ol> 
</nav>    
<p></p>

<?php if ($question->resolved == 1) { ?>
  <h1><?= h($question->title) ?></h1>
  <span class="badge badge-success"><?= __('解決') ?></span> 
<?php }else{ ?>
  <h1><?= h($question->title) ?></h1>
<?php } ?>
<p></p>

<?php foreach ($items as $item){ ?>
  <hr style="margin-bottom:5px;background-color:#c0c0c0;">
  <div class="clearfix mb-2">
    <div class="float-left">
      <span class="font-weight-bold text-primary"><?= h($item->name) ?></span>
      <?php if ($item->url != "") { ?>
        <span>&nbsp;</span><span><a href="<?= h($item->url) ?>" class="badge badge-info"><?= __('URL') ?></a></span>
      <?php } ?>
      <span>&nbsp;</span><span><?= h($item->updated_at->format('Y-m-d')) ?></span>
      <span class="pc">
        <span>&nbsp;No: </span> 
        <span><?= h($item->id) ?></span> 
      </span>
      <?php if ($session->check('user.name')){ ?>
        <span>&nbsp;IP: </span> 
        <span><?= h($item->ip) ?></span>
      <?php } ?>
    </div>    
  </div>
  <div class="clearfix">
    <div class="float-none"></div>  
  </div>  
  <p><?= $item->body ?></p>
  <?php if ($session->check('user.name')){ ?>
    <span><a href="<?= $this->Url->build('/answers/edit/' . $item->id, true) ?>" class="btn btn-primary mr-3"><?= __('編集') ?></a></span>
    <span> <a href="#" onclick="ajax_delete('<?= __('「No.{0}」を削除します。よろしいですか？', $item->id) ?>','<?= $this->Url->build('/answers/delete/' . $item->id, true) ?>','<?= $this->Url->build('/answers?question_id=' .  $question->id, true) ?>');return false;" class="btn btn-danger"><?= __('削除') ?></a></span>
  <?php } ?>   
<?php } ?>
<?php 
  // foreachの$itemが残存してるのでNULLへ
  $item = null;
?>

<hr style="margin-bottom:5px;background-color:#c0c0c0;"> 
<p></p>
<?php
    echo $this->Form->create($entities, ['type' =>'post',
                                         'url' => $this->Url->build('/answers/add?question_id=' . $question->id, true),
                                         'novalidate' => false, // ※HTML5のValidation機能
                                         'id' => 'main_form'
                                        ]);
    echo $this->Form->control('answers.name',    ['label' => ['text' => __('名前')],     'class' => 'form-control', 'required' => 'required']);
    echo '<p></p>';
    echo $this->Form->control('answers.url',     ['label' => ['text' => __('ホームページ(ブログ、Twitterなど)のURL (省略可)')], 'class' => 'form-control']);
    echo '<p></p>';
    echo $this->Form->control('answers.body',    ['label' => ['text' => __('本文')],     'class' => 'form-control','type' =>'textarea', 'required' => 'required']);
    echo '<p></p>';    
    echo $this->Form->control('questions.resolved', ['label' => ['text' => __(' ←解決時は質問者本人がここをチェックしてください。')]]);
    echo '<p><br></p>';    
    
    echo $this->Form->button(__('返信する'), ['class' => 'btn btn-primary', 'id' => 'btn_submit', 'onclick' => 'DisableButton();']);
    echo $this->Form->end();
?>

<br>
<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="<?= $this->Url->build('/' , true)  ?>"><?= __('トップ') ?></a></li> 
    <li class="breadcrumb-item"><a href="<?= $this->Url->build('/questions?lang_id=' . $lang_id , true)  ?>"><?= h($lang_name) ?></a></li> 
    <li class="breadcrumb-item active"><?= h($question->title) ?></li>     
  </ol> 
</nav>    
<p></p>
