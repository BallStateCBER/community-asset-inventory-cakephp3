<?php
namespace App\Model\Table;

use App\Model\Entity\Score;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validator;

/**
 * Counties Model
 *
 * @property \App\Model\Table\StatesTable|\Cake\ORM\Association\BelongsTo $States
 * @property \App\Model\Table\ScoresTable|\Cake\ORM\Association\HasMany $Scores
 *
 * @method \App\Model\Entity\County get($primaryKey, $options = [])
 * @method \App\Model\Entity\County newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\County[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\County|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\County patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\County[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\County findOrCreate($search, callable $callback = null, $options = [])
 */
class CountiesTable extends Table
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

        $this->setTable('counties');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->belongsTo('States', [
            'foreignKey' => 'state_id',
            'joinType' => 'INNER'
        ]);
        $this->hasMany('Scores', [
            'foreignKey' => 'county_id'
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
            ->scalar('name')
            ->maxLength('name', 100)
            ->requirePresence('name', 'create')
            ->notEmpty('name');

        $validator
            ->scalar('simplified')
            ->maxLength('simplified', 100)
            ->requirePresence('simplified', 'create')
            ->notEmpty('simplified');

        $validator
            ->scalar('county_seat')
            ->maxLength('county_seat', 200)
            ->requirePresence('county_seat', 'create')
            ->notEmpty('county_seat');

        $validator
            ->integer('fips')
            ->requirePresence('fips', 'create')
            ->notEmpty('fips');

        $validator
            ->scalar('founded')
            ->maxLength('founded', 4)
            ->requirePresence('founded', 'create')
            ->notEmpty('founded');

        $validator
            ->requirePresence('square_miles', 'create')
            ->notEmpty('square_miles');

        $validator
            ->scalar('description')
            ->requirePresence('description', 'create')
            ->notEmpty('description');

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
        $rules->add($rules->existsIn(['state_id'], 'States'));

        return $rules;
    }

    /**
     * Custom finder for retrieving only root-level categories
     *
     * @param Query $query Cake ORM query
     * @return Query
     */
    public function findIndiana(Query $query)
    {
        $indianaStateId = 14;

        return $query
            ->where(['state_id' => $indianaStateId])
            ->orderAsc('name');
    }

    /**
     * Returns an array of this county's grade scores (if available) and index scores for each category
     *
     * @param int $countyId ID of county record
     * @param int[] $years Years to pull scores from
     * @return array
     */
    public function getScores($countyId, $years)
    {
        $scores = [];

        $scoresTable = TableRegistry::getTableLocator()->get('Scores');
        $categoriesTable = TableRegistry::getTableLocator()->get('Categories');
        $categories = $categoriesTable
            ->find('threaded')
            ->toArray();
        $yearConditions = [];
        foreach ($years as $year) {
            $yearConditions[] = ['year' => $year];
        }
        foreach ($categories as $category) {
            foreach ($category->children as $childCategory) {
                /** @var Score $score */
                $score = $scoresTable->find()
                    ->where([
                        'category_id' => $childCategory->id,
                        'county_id' => $countyId,
                        'OR' => $yearConditions
                    ])
                    ->first();
                $scores[$category->name][$childCategory->name][$score->year] = $score
                    ? $score->value
                    : null;
            }
        }

        return $scores;
    }
}
