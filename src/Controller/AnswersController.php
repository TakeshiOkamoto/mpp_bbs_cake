<?php
namespace App\Controller;

use App\Controller\AppController;

// 追加分
use Cake\Datasource\ConnectionManager;
use Cake\Core\Exception\Exception;
use Cake\ORM\Exception\PersistenceFailedException;

/**
 * Answers Controller
 *
 * @property \App\Model\Table\AnswersTable $Answers
 *
 * @method \App\Model\Entity\Answers[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class AnswersController extends AppController
{

///////////////////////////////////////////////////////////////////////////////

    public function common()
    {
        // 質問
        $question_id = $this->request->getQuery('question_id');
        if(isset($question_id) && AppController::isNumeric($question_id)){
            $questions_item = $this->Questions->find()->where(['id' => $question_id]);
            if ($questions_item->count() === 0){
                return $this->redirect('/');
            }
        }else{
            return $this->redirect('/');
        }
        $questions_item = $questions_item->toArray();
        
        // 言語
        $lang_id = $questions_item[0]->lang_type_id;
        $lang_types_item = $this->LangTypes->find()->where(['id' => $lang_id]);
        if ($lang_types_item->count() === 0){
            return $this->redirect('/');
        }      
        $lang_types_item = $lang_types_item->toArray();
        
        // 回答
        $items = $this->Answers->find()
                   ->where(['question_id' => $question_id])
                   ->order(['created_at' => 'asc'])
                   ->toArray();

        // タイトル毎の閲覧数の更新
        $questions = $this->Questions->get($questions_item[0]->id);
        $questions->pv = $questions_item[0]->pv +1;
        $questions->setDirty('updated_at', true); // 自動で更新日時を更新させない
        $this->Questions->saveOrFail($questions);
        
        // アクセスカウンター(全体)
        $this->access_counter();
        
        // 文字列をHTML(RAW)に変換する
        for($i=0; $i < count($items); $i++){
            $items[$i]->body = AppController::html($items[$i]->body); 
        }
        
        // 各テーブルのエンティティ
        $questions = $this->Questions->newEntity();
        $answers   = $this->Answers->newEntity();
        $entities = [
                     'questions' => $questions,
                     'answers'   => $answers
                    ];
        
        // ビュー用変数            
        $this->set('lang_id', $lang_types_item[0]->id); 
        $this->set('lang_name', $lang_types_item[0]->name);
        $this->set('question', $questions_item[0]); 
        $this->set(compact('items')); 
        $this->set(compact('entities'));
    }

///////////////////////////////////////////////////////////////////////////////
 
    public function initialize()
    {
        // 親クラスのinitialize()
        parent::initialize();

        // レイアウト
        $this->viewBuilder()->setLayout('main');

        // モデル
        $this->loadModel('LangTypes');
        $this->loadModel('Questions');
        $this->loadModel('Bodies');
    }
    
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null
     */
    public function index()
    {
        $this->common();
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $this->common();
        
        // ビュー用の変数を再取得する
        $question_id = $this->viewVars['question']->id;
        $lang_id = $this->viewVars['lang_id'];
                  
        $questions = $this->Questions->newEntity();
        $answers   = $this->Answers->newEntity();
                                  
        if ($this->request->is('post')) { 
            
            // パラメータ 
            $param = $this->request->getData();
            $param['answers']['name']  = AppController::trim($param['answers']['name']);
            $param['answers']['url']   = AppController::trim($param['answers']['url']);
            $param['answers']['body']  = AppController::trim($param['answers']['body']);
            $param['questions']['resolved']  = AppController::trim($param['questions']['resolved']);
            
            // リクエスト(POST)の書き換え
            $this->request = $this->request->withData('answers.name', $param['answers']['name']);
            $this->request = $this->request->withData('answers.url',  $param['answers']['url']);
            $this->request = $this->request->withData('answers.body', $param['answers']['body']);

            // コネクション
            $con = ConnectionManager::get('default');
            // トランザクション
            $con->begin();
              
            try{
                // answers
                $answers = $this->Answers->patchEntity($answers, $param['answers']);
                $answers->question_id = $question_id;
                $answers->ip =  $this->request->clientIp();            
                $this->Answers->saveOrFail($answers, ['atomic' => false]);

                // questions 
                
                  // 解決フラグ
                  // ※ユーザー側の処理では一度、解決にしたらそのままとする
                  if (isset($param['questions']['resolved']) && $param['questions']['resolved'] == 1){
                    $questions = $this->Questions->get($question_id);
                    $questions->resolved = 1;
                    $this->Questions->saveOrFail($questions, ['atomic' => false]);
                  }
                  
                  // 最終更新日時
                  $questions = $this->Questions->get($question_id);
                  $questions->updated_at = $answers->updated_at;
                  $this->Questions->saveOrFail($questions, ['atomic' => false]);
                
                // bodies
                $this->body_table_update($question_id, false);
                
                // コミット
                $con->commit();
                $this->Flash->success(__('返信しました。'));
                return $this->redirect(['action' => 'index', 'question_id' => $question_id]);
                                
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
           
        // 各テーブルのエンティティ
        $entities = [
                     'questions' => $questions,
                     'answers'   => $answers
                    ];
        $this->set(compact('entities'));
        
        // ビューテンプレートはindexとする
        $this->render('index');
    }

    /**
     * Edit method
     *
     * @param string|null $id Answer id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        // ログインチェック 
        $session = $this->getRequest()->getSession();
        if (!$session->check('user.name')){
            return $this->redirect('/');
        }

        $answers = $this->Answers->get($id, [
            'contain' => [],
        ]);
        $questions = $this->Questions->get($answers->question_id, [
            'contain' => [],
        ]);        
        
        if ($this->request->is(['patch', 'post', 'put'])) {
          
            // パラメータ 
            $param = $this->request->getData();
            $param['answers']['name']  = AppController::trim($param['answers']['name']);
            $param['answers']['url']   = AppController::trim($param['answers']['url']);
            $param['answers']['body']  = AppController::trim($param['answers']['body']);
            $param['questions']['resolved']  = AppController::trim($param['questions']['resolved']);
            
            // リクエスト(POST)の書き換え
            $this->request = $this->request->withData('answers.name', $param['answers']['name']);
            $this->request = $this->request->withData('answers.url',  $param['answers']['url']);
            $this->request = $this->request->withData('answers.body', $param['answers']['body']);

            // コネクション
            $con = ConnectionManager::get('default');
            // トランザクション
            $con->begin();
              
            try{
                // answers
                $answers = $this->Answers->patchEntity($answers, $param['answers']);
                $answers->setDirty('updated_at', true); // 自動で更新日時を更新させない
                $this->Answers->saveOrFail($answers, ['atomic' => false]);

                // questions
                
                  // 解決フラグ
                  // ※管理側の処理では解決、未解決を再設定可能とする
                  $questions->resolved = $param['questions']['resolved'];
                  $questions->setDirty('updated_at', true); // 自動で更新日時を更新させない
                  $this->Questions->saveOrFail($questions, ['atomic' => false]);
                
                // bodies
                $this->body_table_update($questions->id, false);
                
                // コミット
                $con->commit();
                $this->Flash->success(__('更新しました。'));
                return $this->redirect(['action' => 'index', 'question_id' => $answers->question_id]);
                                
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
        
        // 各テーブルのエンティティ
        $entities = [
                     'questions' => $questions,
                     'answers'   => $answers
                    ];
        $this->set(compact('entities'));
    }
    
    /**
     * Delete method
     *
     * @param string|null $id Answer id.
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
        $answers = $this->Answers->get($id);

        // コネクション
        $con = ConnectionManager::get('default');
        // トランザクション
        $con->begin();
          
        try{      
            // answers
            $this->Answers->deleteOrFail($answers, ['atomic' => false]);
            
            // questions ※最終更新日時
            $items = $this->Answers->find()->where(['question_id' => $answers->question_id])->order(['updated_at' => 'desc']);
            if ($items->count() !== 0){
                $items = $items->toArray();
                $questions = $this->Questions->get($items[0]->question_id);
                $questions->updated_at = $items[0]->updated_at;
                $this->Questions->saveOrFail($questions, ['atomic' => false]);
            }

            // bodies
            $this->body_table_update($answers->question_id, false);

            // コミット
            $con->commit();
            $this->Flash->error(__('削除しました。'));

        // ロールバック                
        } catch (Exception $e) {
            $con->rollback();
            $this->Flash->error(__('エラーが発生しました。'));
        }
        
        die;
    }
}
