<?php
  $this->assign('title', __('ログイン'));
?>
<p></p>
<h1><?= __('ログイン') ?></h1>
<p></p>
<?php
    echo $this->Form->create(null, ['type' =>'post',
                                    'url' => $this->Url->build('/admin/login', true),
                                    'novalidate' => true, // ※HTML5のValidation機能
                                    'id' => 'main_form'
                                   ]);    
        echo $this->Form->control('email',    ['type' =>'text' ,
                                               'label' => ['text' => __('メールアドレス')], 
                                               'class' => 'form-control', 
                                               'value' => '']);    
        echo $this->Form->control('password', ['type' =>'password' ,
                                               'label' => ['text' => __('パスワード')], 
                                               'class' => 'form-control', 
                                               'value' => '']); 
        echo $this->Form->button(__('ログインする'), ['class' => 'btn btn-primary', 'id' => 'btn_submit', 'onclick' => 'DisableButton(this);']);  
    echo  $this->Form->end(); 
?>

<p><br></p>