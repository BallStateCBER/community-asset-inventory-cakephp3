<?php
namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * States Model
 *
 * @property \App\Model\Table\CountiesTable|\Cake\ORM\Association\HasMany $Counties
 *
 * @method \App\Model\Entity\State get($primaryKey, $options = [])
 * @method \App\Model\Entity\State newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\State[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\State|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\State patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\State[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\State findOrCreate($search, callable $callback = null, $options = [])
 */
class StatesTable extends Table
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

        $this->setTable('states');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->hasMany('Counties', [
            'foreignKey' => 'state_id'
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
            ->integer('id');

        $validator
            ->scalar('name')
            ->maxLength('name', 100)
            ->requirePresence('name', 'create')
            ->allowEmptyString('name', 'State name cannot be empty', false);

        $validator
            ->scalar('abbreviation')
            ->maxLength('abbreviation', 4)
            ->requirePresence('abbreviation', 'create')
            ->allowEmptyString('abbreviation', 'State abbreviation cannot be empty', false);

        $validator
            ->integer('fips')
            ->requirePresence('fips', 'create');

        $validator
            ->boolean('supported')
            ->requirePresence('supported', 'create');

        return $validator;
    }
}
