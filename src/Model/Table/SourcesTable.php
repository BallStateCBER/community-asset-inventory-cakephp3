<?php
namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Sources Model
 *
 * @property \App\Model\Table\CategoriesTable|\Cake\ORM\Association\BelongsToMany $Categories
 *
 * @method \App\Model\Entity\Source get($primaryKey, $options = [])
 * @method \App\Model\Entity\Source newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Source[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Source|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Source patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Source[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Source findOrCreate($search, callable $callback = null, $options = [])
 */
class SourcesTable extends Table
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

        $this->setTable('sources');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->belongsToMany('Categories', [
            'foreignKey' => 'source_id',
            'targetForeignKey' => 'category_id',
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
            ->requirePresence('name', 'create')
            ->allowEmptyString('name', 'Source name cannot be empty', false);

        return $validator;
    }
}
