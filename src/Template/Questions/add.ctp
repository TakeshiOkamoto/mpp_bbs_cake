<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Question $questions
 */
 
 $this->assign('title', __('新規質問の作成 - {0}', h($lang_name)));
?>
<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="<?= $this->Url->build('/' , true)  ?>"><?= __('トップ') ?></a></li> 
    <li class="breadcrumb-item"><a href="<?= $this->Url->build('/questions?lang_id=' . $lang_id , true)  ?>"><?= h($lang_name) ?></a></li> 
    <li class="breadcrumb-item active"><?= __('新規質問の作成') ?></li>     
  </ol> 
</nav>
<p></p>
<h1><?= __('新規質問の作成 - {0}', h($lang_name)) ?></h1>
<p></p>

<?php
    echo $this->Form->create($entities, ['type' =>'post',
                                         'url' => $this->Url->build('/questions/add?lang_id=' . $lang_id, true),
                                         'novalidate' => false, // ※HTML5のValidation機能
                                         'id' => 'main_form'
                                        ]);
    echo $this->Form->control('questions.title', ['label' => ['text' => __('タイトル')], 'class' => 'form-control', 'required' => 'required']);
    echo $this->Form->control('answers.name',    ['label' => ['text' => __('名前')],     'class' => 'form-control', 'required' => 'required']);
    echo $this->Form->control('answers.url',     ['label' => ['text' => __('ホームページ(ブログ、Twitterなど)のURL (省略可)')], 'class' => 'form-control']);
    echo $this->Form->control('answers.body',    ['label' => ['text' => __('本文')],     'class' => 'form-control','type' =>'textarea', 'required' => 'required']);
    
    echo $this->Form->button(__('作成する'), ['class' => 'btn btn-primary', 'id' => 'btn_submit', 'onclick' => 'DisableButton(this);']);
    echo $this->Form->end();
?>
<br>
<p><a href="<?= $this->Url->build('/questions?lang_id=' . $lang_id, true) ?>"><?= __('戻る') ?></a></p>
<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="<?= $this->Url->build('/' , true)  ?>"><?= __('トップ') ?></a></li> 
    <li class="breadcrumb-item"><a href="<?= $this->Url->build('/questions?lang_id=' . $lang_id , true)  ?>"><?= h($lang_name) ?></a></li> 
    <li class="breadcrumb-item active"><?= __('新規質問の作成') ?></li>     
  </ol> 
</nav>
<p></p>