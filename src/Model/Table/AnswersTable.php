<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Answers Model
 *
 * @property \App\Model\Table\QuestionsTable&\Cake\ORM\Association\BelongsTo $Questions
 *
 * @method \App\Model\Entity\Answer get($primaryKey, $options = [])
 * @method \App\Model\Entity\Answer newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Answer[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Answer|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Answer saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Answer patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Answer[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Answer findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class AnswersTable extends AppTable
{
    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('answers');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        // CakePHP標準はcreated/modified
        // $this->addBehavior('Timestamp');
        
        // 以下にするとRails/Laravelと同じ
        $this->addBehavior('Timestamp', [
            'events' => [
                'Model.beforeSave' => [
                    'created_at' => 'new',
                    'updated_at' => 'always'
                ]
            ]
        ]);

        // $this->belongsTo('Questions');
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->integer('id')
            ->allowEmptyString('id', null, 'create');

        $validator
            ->scalar('name')
            ->maxLength('name', 50)
            ->requirePresence('name', 'create')
            ->notEmptyString('name')
            
            // NGワード
            ->add('name', [
                'ngword' => [
                    'rule' => function($value, $context) { 
                        foreach(AppTable::$NG_WORDS as $word) {
                            // 禁止用語が含まれていれば
                            if(strpos($value, $word) !== false){
                                return false;  
                            }
                        }
                        return true; 
                    },
                    'message' => __('文字に禁止用語が含まれています。')
                ],
            ]);            

        $validator
            ->scalar('url')
            ->maxLength('url', 255)
            ->allowEmptyString('url')

            // CakePHPのurlバリデーションは次のようなURLをエラーとするので注意
            // http://localhost:8765/
            ->url('url', __('URLを正しく設定ください。'));

        $validator
            ->scalar('body')
            ->requirePresence('body', 'create')
            ->maxLength('body', 30000)
            ->notEmptyString('body')
            
            // NGワード
            ->add('body', [
                'ngword' => [
                    'rule' => function($value, $context) { 
                        foreach(AppTable::$NG_WORDS as $word) {
                            // 禁止用語が含まれていれば
                            if(strpos($value, $word) !== false){
                                return false;  
                            }
                        }
                        return true; 
                    },
                    'message' => __('文字に禁止用語が含まれています。')
                ],
            ]);             

        $validator
            ->scalar('ip')
            ->maxLength('ip', 255)
            ->requirePresence('ip', 'create')
            ->notEmptyString('ip');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules)
    {
        // 不要
        // $rules->add($rules->existsIn(['question_id'], 'Questions'));

        return $rules;
    }
}
