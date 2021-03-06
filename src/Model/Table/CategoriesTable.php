<?php
namespace App\Model\Table;

use App\Model\Entity\Category;
use Cake\Database\Expression\QueryExpression;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Utility\Hash;
use Cake\Validation\Validator;

/**
 * Categories Model
 *
 * @property \App\Model\Table\CategoriesTable|\Cake\ORM\Association\BelongsTo $ParentCategories
 * @property \App\Model\Table\CategoriesTable|\Cake\ORM\Association\HasMany $ChildCategories
 * @property \App\Model\Table\ScoresTable|\Cake\ORM\Association\HasMany $Scores
 * @property \App\Model\Table\SourcesTable|\Cake\ORM\Association\BelongsToMany $Sources
 *
 * @method \App\Model\Entity\Category get($primaryKey, $options = [])
 * @method \App\Model\Entity\Category newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Category[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Category|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Category patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Category[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Category findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TreeBehavior
 */
class CategoriesTable extends Table
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

        $this->setTable('categories');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Tree');

        $this->belongsTo('ParentCategories', [
            'className' => 'Categories',
            'foreignKey' => 'parent_id'
        ]);
        $this->hasMany('ChildCategories', [
            'className' => 'Categories',
            'foreignKey' => 'parent_id'
        ]);
        $this->hasMany('Scores', [
            'foreignKey' => 'category_id'
        ]);
        $this->belongsToMany('Sources', [
            'foreignKey' => 'category_id',
            'targetForeignKey' => 'source_id',
            'joinTable' => 'categories_sources'
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
            ->maxLength('name', 200)
            ->requirePresence('name', 'create')
            ->allowEmptyString('name', 'Category name cannot be empty', false);

        $validator
            ->scalar('slug')
            ->maxLength('name', 50)
            ->requirePresence('name', 'create')
            ->allowEmptyString('name', 'Slug name cannot be empty', false);

        $validator
            ->integer('weight')
            ->requirePresence('weight', 'create');

        $validator
            ->scalar('description')
            ->requirePresence('description', 'create')
            ->allowEmptyString('description', 'Description cannot be empty', false);

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
        $rules->add($rules->existsIn(['parent_id'], 'ParentCategories'));

        return $rules;
    }

    /**
     * Custom finder for retrieving only root-level categories
     *
     * @param Query $query Cake ORM query
     * @return Query
     */
    public function findParentCategories(Query $query)
    {
        return $query
            ->where(function (QueryExpression $exp) {
                return $exp->isNull('parent_id');
            })
            ->orderAsc('name');
    }

    /**
     * Returns an array of grade scores (if available) and index scores for each county, keyed by county ID
     *
     * @param int $parentCategoryId ID of parent category to the relevant Grade and Index categories
     * @param int $year Year to pull scores from
     * @return array
     */
    public function getScores($parentCategoryId, $year)
    {
        $scores = [
            'grade' => [],
            'index' => []
        ];
        foreach (array_keys($scores) as $categoryName) {
            /** @var Category $childCategory */
            $childCategory = $this->find()
                ->where([
                    'name' => ucwords($categoryName),
                    'parent_id' => $parentCategoryId
                ])
                ->contain([
                    'Scores' => function (Query $query) use ($year) {
                        return $query
                            ->select(['category_id', 'county_id', 'value'])
                            ->where([
                                'is_state_average' => false,
                                'year' => $year
                            ]);
                    }
                ])
                ->first();
            $scores[$categoryName] = $childCategory
                ? Hash::combine($childCategory->scores, '{n}.county_id', '{n}.value')
                : null;
        }

        return $scores;
    }
}
