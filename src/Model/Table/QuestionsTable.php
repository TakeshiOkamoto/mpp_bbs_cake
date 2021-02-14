<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Questions Model
 *
 * @property \App\Model\Table\LangTypesTable&\Cake\ORM\Association\BelongsTo $LangTypes
 * @property \App\Model\Table\AnswersTable&\Cake\ORM\Association\HasMany $Answers
 *
 * @method \App\Model\Entity\Question get($primaryKey, $options = [])
 * @method \App\Model\Entity\Question newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Question[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Question|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Question saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Question patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Question[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Question findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class QuestionsTable extends AppTable
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

        $this->setTable('questions');
        $this->setDisplayField('title');
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

        // $this->hasMany('Answers');
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
            ->scalar('title')
            ->maxLength('title', 150)
            ->requirePresence('title', 'create')
            ->notEmptyString('title')
            
            // NGワード
            ->add('title', [
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
            ])
                        
            // この時点でテーブルチェックをするので
            // validationDefault()の後に実行されるbuildRules()の時でも良い
            ->add('title', 'unique', ['rule' => 'validateUnique', 'provider' => 'table', 'message' => __('タイトルの値は既に存在しています。')]);
                        
        $validator
            ->boolean('resolved')
            ->allowEmptyString('resolved');

        $validator
            ->requirePresence('pv', 'create')
            ->notEmptyString('pv');

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
        // $rules->add($rules->isUnique(['title'], __('タイトルの値は既に存在しています。')));
        // $rules->add($rules->existsIn(['lang_type_id'], 'LangTypes'));

        return $rules;
    }
}
