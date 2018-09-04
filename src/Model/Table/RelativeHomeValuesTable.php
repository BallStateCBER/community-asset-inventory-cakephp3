<?php
namespace App\Model\Table;

use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * RelativeHomeValues Model
 *
 * @property \App\Model\Table\CountiesTable|\Cake\ORM\Association\BelongsTo $Counties
 *
 * @method \App\Model\Entity\RelativeHomeValue get($primaryKey, $options = [])
 * @method \App\Model\Entity\RelativeHomeValue newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\RelativeHomeValue[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\RelativeHomeValue|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\RelativeHomeValue|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\RelativeHomeValue patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\RelativeHomeValue[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\RelativeHomeValue findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class RelativeHomeValuesTable extends Table
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

        $this->setTable('relative_home_values');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Counties', [
            'foreignKey' => 'county_id',
            'joinType' => 'INNER'
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
            ->allowEmpty('id', 'create');

        $validator
            ->boolean('is_neighboring')
            ->requirePresence('is_neighboring', 'create')
            ->notEmpty('is_neighboring');

        $validator
            ->boolean('is_ratio')
            ->requirePresence('is_ratio', 'create')
            ->notEmpty('is_ratio');

        $validator
            ->boolean('is_growth')
            ->requirePresence('is_growth', 'create')
            ->notEmpty('is_growth');

        $validator
            ->integer('year')
            ->requirePresence('year', 'create')
            ->notEmpty('year');

        $validator
            ->numeric('value')
            ->requirePresence('value', 'create')
            ->notEmpty('value');

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
        $rules->add($rules->existsIn(['county_id'], 'Counties'));

        return $rules;
    }
}
