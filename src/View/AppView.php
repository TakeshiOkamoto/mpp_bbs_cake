<?php
/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link      https://cakephp.org CakePHP(tm) Project
 * @since     3.0.0
 * @license   https://opensource.org/licenses/mit-license.php MIT License
 */
namespace App\View;

use Cake\View\View;

/**
 * Application View
 *
 * Your application's default view class
 *
 * @link https://book.cakephp.org/3/en/views.html#the-app-view
 */
class AppView extends View
{
    /**
     * Initialization hook method.
     *
     * Use this method to add common initialization code like loading helpers.
     *
     * e.g. `$this->loadHelper('Html');`
     *
     * @return void
     */
    public function initialize()
    {
        // ----------------
        //  セッション
        // ----------------
        $session = $this->getRequest()->getSession();
        $this->set(compact('session'));
        
        // ----------------
        //  Bootstrap仕様      
        // ----------------

        // "Form"のerrorClass、templatesなどのデフォルト設定は
        // vendor\cakephp\cakephp\src\View\Helper\FormHelper.phpの$_defaultConfigを参照
        
        // エラー時のinputのclass設定
        $this->loadHelper('Form', [
          'errorClass' => 'is-invalid',
        ]);        

        // templatesの内容を変更する
        if ($this->request->getParam('action') == "index"){
                    
            // Questions(検索用)
            if ($this->request->getParam('controller') == "Questions"){
                $this->Form->setTemplates([
                   'inputContainer' =>'<div class="form-group row">{{content}}</div>',
                   'input' => '<div class="col-sm-10"><input type="{{type}}" name="{{name}}"{{attrs}}/></div>',
                ]);   
            // LangTypes(検索用)
            }else{
                $this->Form->setTemplates([
                   'inputContainer' =>'{{content}}',
                ]);
            } 
        }else{
            // 登録/更新
            $this->Form->setTemplates([
               'inputContainer' =>'<div class="form-group">{{content}}</div>',
               'inputContainerError' =>'<div class="form-group">{{content}}{{error}}</div>',
               'error' => '<div class="text-danger" style="font-size:90%">{{content}}</div><p></p>'
            ]);
        }
        
        // "Paginator"のtemplatesなどのデフォルト設定は
        // vendor\cakephp\cakephp\src\View\Helper\PaginatorHelper.phpの$_defaultConfigを参照
                
        // templatesの内容を変更する        
        $this->Paginator->setTemplates([
            'prevActive' => '<li class="page-item"><a class="page-link" href="{{url}}" rel="prev">{{text}}</a></li>',
            'prevDisabled' => '<li class="page-item disabled"><span class="page-link">{{text}}</span></li>',
            'number' => '<li class="page-item"><a class="page-link" href="{{url}}">{{text}}</a></li>',
            'current' => '<li class="page-item active"><span class="page-link" >{{text}}</span></li>',
            'nextActive' => '<li class="page-item"><a rel="next" class="page-link" href="{{url}}">{{text}}</a></li>',
            'nextDisabled' => '<li class="page-item disabled"><span class="page-link">{{text}}</span></li>',            
        ]);
        
    }
}
