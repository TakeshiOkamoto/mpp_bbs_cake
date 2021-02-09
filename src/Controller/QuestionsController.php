<?php
namespace App\Controller;

use App\Controller\AppController;

// 追加分
use Cake\Datasource\ConnectionManager;
use Cake\Core\Exception\Exception;
use Cake\ORM\Exception\PersistenceFailedException;

/**
 * Questions Controller
 *
 * @property \App\Model\Table\QuestionsTable $Questions
 *
 * @method \App\Model\Entity\Questions[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class QuestionsController extends AppController
{
    // ページネーション
    public $paginate = [
        'limit' => 25, 
        'order' => [
            'Questions.updated_at' => 'desc'
        ]
    ];
      
    public function initialize()
    {
       // 親クラスのinitialize()
        parent::initialize();

        // ページネーション
        $this->loadComponent('Paginator');
            
        // レイアウト
        $this->viewBuilder()->setLayout('main');

        // モデル
        $this->loadModel('LangTypes');
        $this->loadModel('Answers');
        $this->loadModel('Bodies');
    }
    
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null
     */
    public function index()
    {   
        // カテゴリID
        $lang_id = $this->request->getQuery('lang_id');
        if(isset($lang_id) && AppController::isNumeric($lang_id)){
            $lang_types_item = $this->LangTypes->find()->where(['id' => $lang_id]);
            if ($lang_types_item->count() === 0){
                return $this->redirect('/');
            }      
        }else{
            return $this->redirect('/');
        }
              
        // 検索キーワード
        $title = "";
        if ($this->request->getQuery('title') !== null){
            $title = AppController::trim($this->request->getQuery('title'));
        }
        $body = "";
        if ($this->request->getQuery('body') !== null){
            $body = AppController::trim($this->request->getQuery('body'));
        }

        // LEFT JOIN(bodies)、WHERE(言語)、SELECT(選択)
        $query = $this->Questions->find()
                   ->leftJoin(['Bodies' => 'bodies'], ['Questions.id = Bodies.question_id'])
                   ->where(['lang_type_id' =>  $lang_id])
                   ->select(['Questions.id', 'Questions.title', 'Questions.resolved', 'Questions.pv', 'Questions.updated_at', 'Bodies.matome']);
                  
        // WHERE(タイトル)
        if ($title != ""){
            $arr = explode(' ', $title);
            for ($i=0; $i<count($arr); $i++){
                $keyword = str_replace('%', '\%', $arr[$i]); 
                $query = $query->where(['title like' => '%' . $keyword . '%']);
            }
        }
        
        // WHERE(本文)
        if ($body != ""){
            $arr = explode(' ', $body);
            for ($i=0; $i<count($arr); $i++){
                $keyword = str_replace('%', '\%', $arr[$i]); 
                $query = $query->where(['matome like' => '%' . $keyword . '%']);
            }
        }        
        
        // 質問者、最終発言者、件数の配列を取得する
        $sql =" SELECT " .
              "  questions.id," .
              "  (SELECT answers.name FROM answers WHERE answers.question_id = questions.id  ORDER BY answers.id ASC LIMIT 1) as name1," .
              "  (SELECT answers.name FROM answers WHERE answers.question_id = questions.id  ORDER BY answers.id DESC LIMIT 1) as name2," .
              "  (SELECT count(id) FROM answers WHERE answers.question_id = questions.id) as cnt " .
              " FROM questions  " .
              " WHERE " .
              "  questions.lang_type_id = :lang_type_id" .
              " ORDER BY" .  
              "  questions.updated_at DESC";

        // SQLの発行
        $con = ConnectionManager::get('default');
        $db_data = $con->execute($sql, ['lang_type_id' => $lang_id])->fetchAll('assoc');
        
        // アクセスカウンター(全体)
        $this->access_counter();
        
        $this->set(compact('title'));
        $this->set(compact('body')); 
        
        $this->set(compact('db_data'));     
        $this->set('items', $this->paginate($query));
        $this->set('lang_types_item', $lang_types_item->toArray()[0]);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {   
        // カテゴリID
        $lang_id = $this->request->getQuery('lang_id');
        if(isset($lang_id) && AppController::isNumeric($lang_id)){
            $item = $this->LangTypes->find()->where(['id' => $lang_id]);
            if ($item->count() === 0){
                return $this->redirect('/');
            }      
            $item = $item->toArray();
            $lang_name = $item[0]->name;
        }else{
            return $this->redirect('/');
        }
        
        $questions = $this->Questions->newEntity();
        $answers   = $this->Answers->newEntity();
                                  
        if ($this->request->is('post')) { 
       
            // パラメータ 
            $param = $this->request->getData();
            $param['questions']['title']  = AppController::trim($param['questions']['title']);
            $param['answers']['name']     = AppController::trim($param['answers']['name']);
            $param['answers']['url']      = AppController::trim($param['answers']['url']);
            $param['answers']['body']     = AppController::trim($param['answers']['body']);

            $param['questions']['lang_type_id'] = $lang_id;
            $param['questions']['resolved'] = false;
            $param['questions']['pv'] = 0;
            
            // リクエスト(POST)の書き換え
            $this->request = $this->request->withData('questions.title', $param['questions']['title']);
            $this->request = $this->request->withData('answers.name', $param['answers']['name']);
            $this->request = $this->request->withData('answers.url',  $param['answers']['url']);
            $this->request = $this->request->withData('answers.body', $param['answers']['body']);
                        
            $questions = $this->Questions->patchEntity($questions, $param['questions']);
            $answers   = $this->Answers->patchEntity($answers, $param['answers']);
                            
            // コネクション
            $con = ConnectionManager::get('default');
            // トランザクション
            $con->begin();
              
            try{
                // ------------------------------------------------------------
                //  save()ができない場合は例外(PersistenceFailedException)
                // ------------------------------------------------------------
                // questions
                $this->Questions->saveOrFail($questions, ['atomic' => false]);
                
                // answers
                $answers['question_id'] = $questions['id'];
                $answers['ip'] = $this->request->clientIp();
                $this->Answers->saveOrFail($answers, ['atomic' => false]);
                
                // bodies
                $this->body_table_update($questions['id'], false);
                            
                // コミット
                $con->commit();
                $this->Flash->success(__('登録しました。'));
                return $this->redirect(['action' => 'index', 'lang_id'=> $lang_id]);
                
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
                            
        $this->set(compact('lang_name'));
        $this->set(compact('lang_id'));
        $this->set(compact('entities'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Question id.
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
        $questions = $this->Questions->get($id);
        
        // コネクション
        $con = ConnectionManager::get('default');
        // トランザクション
        $con->begin();
          
        try{
            // ------------------------------------------------------------
            //  delete()ができない場合は例外(PersistenceFailedException)
            // ------------------------------------------------------------
         
            // answers
            $answers = $this->Answers->find()->where(['question_id' => $id]);
            foreach($answers as $answer){
                $this->Answers->deleteOrFail($answer, ['atomic' => false]);
            }
 
            // questions
            $this->Questions->deleteOrFail($questions, ['atomic' => false]);
            
            // bodies
            $this->body_table_update($id, true);
            
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
