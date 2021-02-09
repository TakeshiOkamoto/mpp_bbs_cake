<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Answer $answers
 */

 $this->assign('title', __('編集'));
?>

<p></p>
<h1><?= h($entities['questions']->title) . __(' (ID:{0})', $entities['answers']->id)?></h1>
<p></p>
<?php
    echo $this->Form->create($entities, ['type' =>'put',
                                         'url' => $this->Url->build('/answers/edit/' . $entities['answers']->id, true),
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
    echo '<br>';
    
    echo $this->Form->button(__('更新する'), ['class' => 'btn btn-primary', 'id' => 'btn_submit', 'onclick' => 'DisableButton();']);
    echo $this->Form->end();
?>
<br>
<p><a href="<?= $this->Url->build('/answers?question_id=' . $entities['questions']->id, true) ?>"><?= __('戻る') ?></a></p>