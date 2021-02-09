<?php
namespace App\Controller;

use App\Controller\AppController;

// 追加分
use Cake\Datasource\ConnectionManager;

class HomeController extends AppController
{
    public function initialize()
    {
        // 親クラスのinitialize()
        parent::initialize();

        // レイアウト
        $this->viewBuilder()->setLayout('main');

        // モデル
        $this->loadModel('LangTypes');
    }
    
    public function index()
    {
        // カテゴリ
        $items = $this->LangTypes->find()->order(['sort' => 'asc']);
        $items = $items->toArray();

        // 質問数 コメント数、未返信数、解決数 閲覧数
        $sql = "SELECT ".
               "  (SELECT COUNT(id) FROM questions WHERE Z.id = questions.lang_type_id) AS A," .
               "   " .
                        
               "(SELECT COUNT(answers.id) FROM answers " .
               "   LEFT JOIN questions ON answers.question_id = questions.id " .
               "   WHERE Z.id = questions.lang_type_id) AS B, " .
     
               // 未返信数 ※返信がないもの =  1件のみ 
               "  (SELECT" .
               "     COUNT(cnt) " .
               "   FROM " .
               "     ( " .
               "      SELECT " .
               "        count(question_id) as cnt,questions.lang_type_id " .
               "      FROM " .
               "        answers " .
               "      INNER JOIN " .
               "        questions ON answers.question_id = questions.id " .
               "      GROUP BY question_id   " .
               "      HAVING  " .
               "       cnt =1  " .
               "      ) AS X " .
               "    WHERE " .
               "     X.lang_type_id = Z.id) AS C, " .
               " " .
               "   (SELECT IFNULL(SUM(CASE WHEN resolved=0 THEN 0 ELSE 1 END),0) FROM questions WHERE Z.id = questions.lang_type_id) AS D, " .
               "   (SELECT IFNULL(SUM(pv),0) FROM questions WHERE Z.id = questions.lang_type_id) AS E " .
               "FROM  " .
               "  lang_types as Z " .
               "ORDER BY ".
               "  Z.sort ASC";
               
        // SQLの発行
        $con = ConnectionManager::get('default');
        $counts = $con->execute($sql, [])->fetchAll('assoc');
        
        // アクセスカウンター(全体)
        $this->access_counter();
        
        $this->set(compact('items')); 
        $this->set(compact('counts'));
    }
}
