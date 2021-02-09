<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\LangType $langType
 */
 
 $this->assign('title', __('カテゴリ - 管理画面'));
?>

<p></p>
<h1><?= h($langType->name) ?></h1>
<p></p>

<p>
  <strong>ID : </strong>
  <?= h($langType->id) ?>
</p>

<p>
  <strong><?= __('キーワード') ?> : </strong>
  <?= h($langType->keywords) ?>
</p>

<p>
  <strong><?= __('説明') ?> : </strong>
</p>
<?= $this->Text->autoParagraph(h($langType->description)) ?>

<p>
  <strong><?= __('ソート') ?> : </strong>
  <?= h($langType->sort) ?>
</p>

<p>
  <strong><?= __('表示') ?> : </strong>
  <?= ($langType->show == 1) ? 1:0; ?>
</p>

<p>
  <strong><?= __('作成日時') ?> : </strong>
  <?= h($langType->created_at) ?>
</p>


<p>
  <strong><?= __('更新日時') ?> : </strong>
  <?= h($langType->updated_at) ?>
</p>

<a href="<?= $this->Url->build('/lang-types/edit/' . $langType->id , true) ?>"><?= __('編集') ?></a> | <a href="<?= $this->Url->build('/lang-types', true) ?>"><?= __('戻る') ?></a>
<p></p>
