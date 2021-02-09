<?php
namespace App\Controller;

use App\Controller\AppController;

// 追加分
use Cake\Auth\DefaultPasswordHasher;

class AdminController extends AppController
{
    public function initialize()
    {
        // 親クラスのinitialize()
        parent::initialize();

        // レイアウト
        $this->viewBuilder()->setLayout('main');

        // モデル
        $this->loadModel('Users');
    }
    
    public function login()
    {   
        // ログイン済み
        $session = $this->getRequest()->getSession();
        if ($session->check('user.name')){
            return $this->redirect('/');
        }
        
        if ($this->request->is('post')) { 
          
            // メールアドレスの確認
            $param = $this->request->getData();
            $users = $this->Users->find()->where(['email' => $param['email']]);
            if ($users->count() === 0){
                $this->Flash->error(__('メールアドレスまたはパスワードが一致しません。'));
                return;
            }
            $users = $users->toArray();

            // パスワードの確認
            $hash = new DefaultPasswordHasher();
            if(!$hash->check($param['password'], $users[0]->password)){
                $this->Flash->error(__('メールアドレスまたはパスワードが一致しません。'));
                return;
            }
            
            // セッション            
            $session->write('user.name',  $users[0]->name);
            $session->write('user.email', $users[0]->email);
            $this->Flash->success(__('ログインしました。'));
            return $this->redirect('/');
        }
    }
    
    public function logout()
    {
        $session = $this->getRequest()->getSession();

        // $session->delete('user.name'); 
        // $session->delete('user.email');
        
        $session->destroy();
        
        return $this->redirect('/');
    }    
}
