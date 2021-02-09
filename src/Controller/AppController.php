<?php
/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link      https://cakephp.org CakePHP(tm) Project
 * @since     0.2.9
 * @license   https://opensource.org/licenses/mit-license.php MIT License
 */
namespace App\Controller;

use Cake\Controller\Controller;
use Cake\Event\Event;

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @link https://book.cakephp.org/3/en/controllers.html#the-app-controller
 */
class AppController extends Controller
{

///////////////////////////////////////////////////////////////////////////////    

    public function initialize()
    {
        parent::initialize();

        $this->loadComponent('RequestHandler', [
            'enableBeforeRedirect' => false,
        ]);
        $this->loadComponent('Flash');
        
        /*
         * Enable the following component for recommended CakePHP security settings.
         * see https://book.cakephp.org/3/en/controllers/components/security.html
         */
        //$this->loadComponent('Security');
    }

///////////////////////////////////////////////////////////////////////////////    

    // 数字チェック
    public static function isNumeric($str){
        if (preg_match("/\A[0-9]+\z/",$str)) {
            if($str <= 2147483647)
            return TRUE;
        else
            return FALSE;
        } else {
            return FALSE;
        }
   } 
       
    // 全角 => 半角変換 + trim
    public static function trim($str){
        if (isset($str)){
            // a 全角英数字を半角へ
            // s 全角スペースを半角へ
            return trim(mb_convert_kana($str, 'as'));
        }else{
            return "";
        }
    }
    
    // URLをリンクに変換する(簡易的)
    // ※CakePHPならば$this->Text->autoLinkUrls()でも良い
    public static function auto_link($text){
        $result = $text;
        
        if(isset($result)){
                          
            // URLパターン(予約文字 + 非予約文字 + %)
            //
            // <参考>
            // https://www.asahi-net.or.jp/~ax2s-kmtn/ref/uric.html
            // https://www.petitmonte.com/php/regular_expression_matome.html
            // 
            // 次のvalidateUrl()も参考になる ※先頭の^ と末尾の$を削除して使用する
            // laravel\framework\src\Illuminate\Validation\Concerns/ValidatesAttributes.php 
            $pattern ='/(http|https):\/\/[!#$%&\'()*+,\/:;=?@\[\]0-9A-Za-z-._~]+/';
            
            // URLをaタグに変換する
            $result = preg_replace_callback($pattern, function ($matches) {   
                        return '<a href="' . $matches[0] . '">'. $matches[0] . '</a>';
                      }, $result);
        }
        return $result;            
    }
        
    // 文字列をHTML(RAW)に変換する
    public static function html($text){
        $result = "";
        
        if(isset($text)){
            // エスケープ
            $result = htmlspecialchars($text);
            // 半角スペース 
            $result = str_replace(" ", '&nbsp;', $result);
            // タブ 
            $result = str_replace("	", '&nbsp;&nbsp;', $result);
            // 改行 
            $result = str_replace("\r\n", '<br>', $result);
            $result = str_replace("\r",   '<br>', $result);
            $result = str_replace("\n",   '<br>', $result);
            
            // URLをaタグに変換する
            // ※既知の問題点 ---> 最初から<a href=""></a>のタグがある場合はその自動リンクが不自然となる
            $result = AppController::auto_link($result);
        }
        return $result;
    }    

///////////////////////////////////////////////////////////////////////////////

    // (検索用)本文テーブルの更新
    public function body_table_update($question_id, $isDeleteOnly){
        
        // モデル
        $this->loadModel('Bodies');
        $this->loadModel('Answers');
        
        // データを削除
        $bodies = $this->Bodies->find()->where(['question_id' => $question_id]);
        if ($bodies->count() === 1){
            $bodies = $bodies->toArray();
            $this->Bodies->deleteOrFail($bodies[0], ['atomic' => false]);
        }      
        
        // 本文を最新の状態にする 
        if(!$isDeleteOnly){
          
            $answers = $this->Answers->find()->where(['question_id' => $question_id]);
            $matome = " "; // 回答が0の場合の為にスペースをいれておく
            
            foreach($answers as $answer){
                $matome = $matome . ' ' . $answer->body;
            }

            // Body
            $param = [
                'question_id' => $question_id,
                'matome'      => $matome,
            ];
            
            $bodies = $this->Bodies->newEntity();
            $bodies = $this->Bodies->patchEntity($bodies, $param);
            $this->Bodies->saveOrFail($bodies, ['atomic' => false]);
        }   
    }
    
    // アクセスカウンター(全体)
    public function access_counter(){
        $yyyy = date("Y");
        $mm   = date("m");
        $dd   = date("d");
      
        // モデル
        $this->loadModel('Accesses');
        
        // データの確認
        $accesses = $this->Accesses->find()
                      ->where(['yyyy' => $yyyy, 'mm' => $mm, 'dd' => $dd]);
        
        // 新規           
        if ($accesses->count() === 0){
            $param = [
                'yyyy' => $yyyy,
                'mm'   => $mm,
                'dd'   => $dd,
                'pv'   => 1
            ];
            
            $accesses = $this->Accesses->newEntity();
            $accesses = $this->Accesses->patchEntity($accesses, $param);
            $this->Accesses->save($accesses);

        // 更新
        }else{
            $accesses = $accesses->toArray();
            $accesses[0]['pv'] = $accesses[0]['pv'] + 1;
            $this->Accesses->save($accesses[0]); 
        }   
    }    
}
