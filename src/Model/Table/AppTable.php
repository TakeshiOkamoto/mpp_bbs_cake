<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

// QuestionsTable、AnswersTableの親クラス
class AppTable extends Table
{
     // 禁止用語 
     // ※各自で追加して下さい
     public static $NG_WORDS =[
         "カジノ",
         "ギャンブル"
     ];
}
