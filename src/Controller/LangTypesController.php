<?php
namespace App\Controller;

use App\Controller\AppController;

// 追加分
use Cake\Event\Event;
use Cake\Datasource\ConnectionManager;
use Cake\Core\Exception\Exception;
use Cake\ORM\Exception\PersistenceFailedException;

/**
 * LangTypes Controller
 *
 * @property \App\Model\Table\LangTypesTable $LangTypes
 *
 * @method \App\Model\Entity\LangType[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class LangTypesController extends AppController
{
    // ページネーション
    public $paginate = [
        'limit' => 10, 
        'order' => [
            'LangTypes.sort' => 'asc'
        ]
    ];
    
    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        
        // ログインチェック 
        $session = $this->getRequest()->getSession();
        if (!$session->check('user.name')){
            return $this->redirect('/');
        }
    }
    
    public function initialize()
    {
        // 親クラスのinitialize()
        parent::initialize();

        // ページネーション
        $this->loadComponent('Paginator');
            
        // レイアウト
        $this->viewBuilder()->setLayout('main');
    }
    
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null
     */
    public function index()
    {   
        // 検索キーワード
        $name = "";
        if ($this->request->getQuery('name') !== null){
            $name = AppController::trim($this->request->getQuery('name'));
        }
        $this->set(compact('name'));

        // 複数件の処理
        $query = $this->LangTypes->find();
        if ($name != ""){
            $arr = explode(' ', $name);
            for ($i=0; $i<count($arr); $i++){
                $keyword = str_replace('%', '\%', $arr[$i]); 
                $query = $query->where(['name like' => '%' . $keyword . '%']);
            }
        }
        // $paginateで定義済み
        // $query->order(['LangTypes.sort' => 'asc']);
        
        $this->set('langTypes', $this->paginate($query));
    }

    /**
     * View method
     *
     * @param string|null $id Lang Type id.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $langType = $this->LangTypes->get($id, [
            'contain' => [],
        ]);

        $this->set('langType', $langType);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $langType = $this->LangTypes->newEntity();
        if ($this->request->is('post')) { 
            
            // パラメータ 
            $param = $this->request->getData();
            $param['name']        = AppController::trim($param['name']);
            $param['keywords']    = AppController::trim($param['keywords']);
            $param['description'] = AppController::trim($param['description']);
            
            // リクエスト(POST)の書き換え          
            $this->request = $this->request->withData('name', $param['name']);
            $this->request = $this->request->withData('keywords', $param['keywords']);
            $this->request = $this->request->withData('description', $param['description']);
                        
            $langType = $this->LangTypes->patchEntity($langType, $param);
          
            // コネクション
            $con = ConnectionManager::get('default');
            // トランザクション
            $con->begin();
              
            try{
                // -------------------------------------------------------------
                //  save()ができない場合は例外(PersistenceFailedException)
                // -------------------------------------------------------------
                $this->LangTypes->saveOrFail($langType, ['atomic' => false]);
                
                // コミット
                $con->commit();
                $this->Flash->success(__('登録しました。'));
                return $this->redirect(['action' => 'index']);
                
            // ロールバック                
            } catch (PersistenceFailedException $e) {
                $con->rollback();
                $this->Flash->error(__('エラーをご確認ください。'));
            } catch (Exception $e) {
                // その他の例外
                $con->rollback();
                $this->Flash->error(__('エラーが発生しました。管理者に問い合わせてください。'));
            }
        }
        $this->set(compact('langType'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Lang Type id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $langType = $this->LangTypes->get($id, [
            'contain' => [],
        ]);
        
        if ($this->request->is(['patch', 'post', 'put'])) {
            
            // パラメータ 
            $param = $this->request->getData();
            $param['name']        = AppController::trim($param['name']);
            $param['keywords']    = AppController::trim($param['keywords']);
            $param['description'] = AppController::trim($param['description']);
            
            // リクエスト(POST)の書き換え          
            $this->request = $this->request->withData('name', $param['name']);
            $this->request = $this->request->withData('keywords', $param['keywords']);
            $this->request = $this->request->withData('description', $param['description']);
            
            $langType = $this->LangTypes->patchEntity($langType, $param);
            
            // コネクション
            $con = ConnectionManager::get('default');
            // トランザクション
            $con->begin();
              
            try{
                // -------------------------------------------------------------
                //  save()ができない場合は例外(PersistenceFailedException)
                // -------------------------------------------------------------
                $this->LangTypes->saveOrFail($langType, ['atomic' => false]);
                
                // コミット
                $con->commit();
                $this->Flash->success(__('更新しました。'));
                return $this->redirect(['action' => 'index']);
                
            // ロールバック                
            } catch (PersistenceFailedException $e) {
                $con->rollback();
                $this->Flash->error(__('エラーをご確認ください。'));
            } catch (Exception $e) {
                // その他の例外
                $con->rollback();
                $this->Flash->error(__('エラーが発生しました。管理者に問い合わせてください。'));
            }
        }
        $this->set(compact('langType'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Lang Type id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        // ログインチェック
        $session = $this->getRequest()->getSession();
        if (!$session->check('user.name')){
            return $this->redirect('/');
        }
              
        $this->request->allowMethod(['post', 'delete']);
        $langType = $this->LangTypes->get($id);
        
        // コネクション
        $con = ConnectionManager::get('default');
        // トランザクション
        $con->begin();
          
        try{
            // -------------------------------------------------------------
            //  delete()ができない場合は例外(PersistenceFailedException)
            // -------------------------------------------------------------
            $this->LangTypes->deleteOrFail($langType, ['atomic' => false]);
            
            // コミット
            $con->commit();
            $this->Flash->error(__('削除しました。'));

        // ロールバック                
        } catch (Exception $e) {
            $con->rollback();
            $this->Flash->error(__('エラーが発生しました。'));
        }
        
        // Ajaxなのでdie(exit)する
        die;
        
        // 標準機能(POST)を使用する場合
        //return $this->redirect(['action' => 'index']);
    }
}
