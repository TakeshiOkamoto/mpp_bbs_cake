<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\LangType $langType
 */
 
 $this->assign('title', __('カテゴリ - 管理画面'));
?>
<p></p>
<h1>編集</h1>
<p></p>
<?php
    echo $this->Form->create($langType, ['type' =>'put',
                                         'url' => $this->Url->build('/lang-types/edit/' . $langType->id , true),
                                         'novalidate' => false // ※HTML5のValidation機能
                                        ]);
    echo $this->Form->control('name',        ['label' => ['text' => __('名前')],       'class' => 'form-control']);
    echo $this->Form->control('keywords',    ['label' => ['text' => __('キーワード')], 'class' => 'form-control']);
    echo $this->Form->control('description', ['label' => ['text' => __('説明')],       'class' => 'form-control']);
    echo $this->Form->control('sort',        ['label' => ['text' => __('ソート')],     'class' => 'form-control']);

    echo '<div style="float:left;">';
    echo $this->Form->control('show', ['label' => ['text' => __('表示')], 'class' => 'form-control']);
    echo '</div>';
    echo '<div style="clear:both;"></div>';
    
    echo $this->Form->button(__('更新する'), ['class' => 'btn btn-primary']);
    echo $this->Form->end();
?>
<br>
<p><a href="<?= $this->Url->build('/lang-types', true) ?>"><?= __('戻る') ?></a></p>

