<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * LangTypes Model
 *
 * @method \App\Model\Entity\LangType get($primaryKey, $options = [])
 * @method \App\Model\Entity\LangType newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\LangType[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\LangType|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\LangType saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\LangType patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\LangType[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\LangType findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class LangTypesTable extends Table
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

        $this->setTable('lang_types');
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
            
            // この時点でテーブルチェックをするので
            // validationDefault()の後に実行されるbuildRules()の時でも良い
            ->add('name', 'unique', ['rule' => 'validateUnique', 'provider' => 'table', 'message' => __('名前の値は既に存在しています。')]);

        $validator
            ->scalar('keywords')
            ->maxLength('keywords', 255)
            ->requirePresence('keywords', 'create')
            ->notEmptyString('keywords');

        $validator
            ->scalar('description')
            ->maxLength('description', 512) 
            ->allowEmptyString('description');

        $validator
            ->integer('sort')
            ->requirePresence('sort', 'create')
            ->range('sort', [0, 1000], __('0 ～ 1000の値を入力してください。'))
            // ->greaterThan('sort', -1, __('マイナスの値は入力できません。'))
            ->notEmptyString('sort');

        $validator
            ->boolean('show')
            ->allowEmptyString('show');

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
        // $rules->add($rules->isUnique(['name'], __('名前の値は既に存在しています。')));

        return $rules;
    }
}
