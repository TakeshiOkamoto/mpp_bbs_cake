<?php
namespace App\Controller;

use App\Controller\AppController;

// 追加分
use Cake\Datasource\ConnectionManager;

class AccessesController extends AppController
{
    public function initialize()
    {
        // 親クラスのinitialize()
        parent::initialize();

        // レイアウト
        $this->viewBuilder()->setLayout('main');

        // ログインチェック 
        // ※CakePHPの仕様でここで定義してもdeleteメソッドには別途、同様な定義が必要。
        $session = $this->getRequest()->getSession();
        if (!$session->check('user.name')){
            return $this->redirect('/');
        }
    }
    
    public function index()
    {
        $con = ConnectionManager::get('default');
              
        // 前月、前年
        $month_ago = date("Y-m-d H:i:s",strtotime("-1 month"));
        $yyyy_ago =  date("Y-m-d H:i:s",strtotime("-1 year"));
        
        // 日毎(1か月分)
        $sql= "SELECT  yyyy,mm,dd,pv FROM accesses " .
              "  WHERE (STR_TO_DATE(yyyy,'%Y') + STR_TO_DATE(mm,'%m')+ STR_TO_DATE(dd,'%d')) >= :yyyymmdd" .
              "  ORDER BY yyyy DESC,mm DESC,dd DESC ";              
        $yyyymmdd = date('Y',strtotime($month_ago)) .
                    date('m',strtotime($month_ago)) .
                    date('d',strtotime($month_ago));
        $one_month_ago = $con->execute($sql, ['yyyymmdd' => $yyyymmdd])->fetchAll('assoc');
        
        // 各月(前年以降)
        $sql= "SELECT  yyyy,mm,TRUNCATE(AVG(pv),0) as pv FROM accesses " .
               "  WHERE yyyy >= :yyyy " .
               "  GROUP BY yyyy,mm " .
               "  ORDER BY yyyy DESC,mm DESC"; 
        $yyyy = date('Y',strtotime($yyyy_ago));        
        $one_year_ago = $con->execute($sql, ['yyyy' => $yyyy])->fetchAll('assoc');
        
        // 曜日の追加
        foreach ($one_month_ago as $index => $item){
           $one_month_ago[$index]['week'] =  date('w', strtotime($item['yyyy'] . '-' . $item['mm'] . '-' . $item['dd'])); 
        }
        
        $this->set(compact('one_month_ago')); 
        $this->set(compact('one_year_ago'));
    }
}
