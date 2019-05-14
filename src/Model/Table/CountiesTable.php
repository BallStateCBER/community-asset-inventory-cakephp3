<?php
namespace App\Model\Table;

use App\Model\Entity\County;
use Cake\Datasource\EntityInterface;
use Cake\ORM\Association\BelongsTo;
use Cake\ORM\Association\HasMany;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validator;

/**
 * Counties Model
 *
 * @property StatesTable|BelongsTo $States
 * @property ScoresTable|HasMany $Scores
 *
 * @method County get($primaryKey, $options = [])
 * @method County newEntity($data = null, array $options = [])
 * @method County[] newEntities(array $data, array $options = [])
 * @method County|bool save(EntityInterface $entity, $options = [])
 * @method County patchEntity(EntityInterface $entity, array $data, array $options = [])
 * @method County[] patchEntities($entities, array $data, array $options = [])
 * @method County findOrCreate($search, callable $callback = null, $options = [])
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
     * @param Validator $validator Validator instance.
     * @return Validator
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
     * @param RulesChecker $rules The rules object to be modified.
     * @return RulesChecker
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
        $categoriesTable = TableRegistry::getTableLocator()->get('Categories');
        $categories = $categoriesTable
            ->find('threaded')
            ->toArray();

        $yearConditions = [];
        foreach ($years as $year) {
            $yearConditions[] = ['year' => $year];
        }

        $retval = [];
        $scoresTable = TableRegistry::getTableLocator()->get('Scores');
        foreach ($categories as $category) {
            foreach ($category->children as $childCategory) {
                $categoryScores = $scoresTable->find()
                    ->where([
                        'category_id' => $childCategory->id,
                        'county_id' => $countyId,
                        'OR' => $yearConditions
                    ])
                    ->all();
                foreach ($categoryScores as $score) {
                    $retval[$category->name][$childCategory->name][$score->year] = $score ? $score->value : null;
                }
            }
        }

        return $retval;
    }
}
