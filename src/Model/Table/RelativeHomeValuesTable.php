<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Utility\Hash;
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

    /**
     * Returns the minimum value for the specified measurement type
     *
     * @param string $type Either growth or ratio
     * @return float
     */
    public function getMinValue($type)
    {
        $query = $this->find();
        $results = $query
            ->select(['min' => $query->func()->min('value')])
            ->where(["is_$type" => true])
            ->first()
            ->toArray();

        return $results['min'];
    }

    /**
     * Returns the maximum value for the specified measurement type
     *
     * @param string $type Either growth or ratio
     * @return float
     */
    public function getMaxValue($type)
    {
        $query = $this->find();
        $results = $query
            ->select(['max' => $query->func()->max('value')])
            ->where(["is_$type" => true])
            ->first()
            ->toArray();

        return $results['max'];
    }

    /**
     * Returns an array of RelativeHomeValue values, keyed by county names
     *
     * @param bool $isNeighboring True if value is only for neighboring counties of each county
     * @param string $type Either ratio or growth
     * @param null|int $countyId County ID, or null for all counties
     * @return array
     */
    private function getResults($isNeighboring, $type, $countyId = null)
    {
        $year = 2017;
        $query = $this->find()
            ->where([
                'year' => $year,
                'is_neighboring' => $isNeighboring,
                "is_$type" => true
            ])
            ->select(['county_id', 'value'])
            ->contain([
                'Counties' => function (Query $q) {
                    return $q->select(['id', 'name']);
                }
            ]);
        if ($countyId) {
            $query->where(['county_id' => $countyId]);
        }
        $results = $query->toArray();

        return Hash::combine($results, '{n}.county.name', '{n}.value');
    }

    /**
     * Returns the growing/recovering/warning/distressed status of a given set of values
     *
     * @param float $growth Housing value growth value
     * @param float $ratio County to state housing value ratio
     * @return string
     */
    private function getStatus($growth, $ratio)
    {
        if ($growth <= $this->getStateGrowthValue()) {
            return $ratio >= 1 ? 'warning' : 'distressed';
        }

        return $ratio >= 1 ? 'growing' : 'recovering';
    }

    /**
     * Returns the state average home value growth
     *
     * @return float
     */
    private function getStateGrowthValue()
    {
        return 0.084;
    }

    /**
     * Returns the hex code for the color that a county with the given values should be displayed in
     *
     * @param float $growth Housing value growth value
     * @param float $ratio County to state housing value ratio
     * @return string
     */
    private function getColor($growth, $ratio)
    {
        $colors = $this->getColors();
        $status = $this->getStatus($growth, $ratio);

        return $colors[$status];
    }

    /**
     * Returns an array of hex codes for colors used in scatter plot
     *
     * @return array
     */
    public function getColors()
    {
        return [
            'growing' => '#109618',
            'recovering' => '#3366cc',
            'warning' => '#ff9900',
            'distressed' => '#dc3912'
        ];
    }

    /**
     * Returns an array of all variables needed to render the housing_barometer element
     *
     * @param null|int $countyId County ID, or null for all counties
     * @return array
     */
    public function getVarsForScatterPlot($countyId = null)
    {
        // Relative home values
        $rhvs = [];

        foreach (['ratio', 'growth'] as $type) {
            $rhvs['counties'][$type] = $this->getResults(false, $type, $countyId);
            $rhvs['neighboring'][$type] = $this->getResults(true, $type, $countyId);
        }

        $chartData = [
            [
                'Growth value',

                ['label' => 'Growing', 'type' => 'number'],
                ['type' => 'string', 'role' => 'tooltip'],
                ['type' => 'string', 'role' => 'style'],
                ['type' => 'string', 'role' => 'annotation'],

                ['label' => 'Recovering', 'type' => 'number'],
                ['type' => 'string', 'role' => 'tooltip'],
                ['type' => 'string', 'role' => 'style'],
                ['type' => 'string', 'role' => 'annotation'],

                ['label' => 'Warning', 'type' => 'number'],
                ['type' => 'string', 'role' => 'tooltip'],
                ['type' => 'string', 'role' => 'style'],
                ['type' => 'string', 'role' => 'annotation'],

                ['label' => 'Distressed', 'type' => 'number'],
                ['type' => 'string', 'role' => 'tooltip'],
                ['type' => 'string', 'role' => 'style'],
                ['type' => 'string', 'role' => 'annotation']
            ],
        ];
        $countyNames = array_keys($rhvs['counties']['ratio']);
        $columns = [
            'growing' => 1,
            'recovering' => 5,
            'warning' => 9,
            'distressed' => 13
        ];
        foreach ($countyNames as $countyName) {
            foreach (['counties', 'neighboring'] as $subject) {
                // Hide 'neighboring' dots for the all-counties chart
                if (!$countyId && $subject == 'neighboring') {
                    continue;
                }

                $growth = $rhvs[$subject]['growth'][$countyName];
                $ratio = $rhvs[$subject]['ratio'][$countyName];
                $status = $this->getStatus($growth, $ratio);
                $tooltip = sprintf(
                    '%s (%s)',
                    $subject == 'counties'
                        ? "$countyName County"
                        : "Counties neighboring $countyName County",
                    $status
                );
                $annotation = $subject == 'counties'
                    ? "$countyName County"
                    : "Neighboring counties";
                $strokeColor = $this->getColor($growth, $ratio);
                $fillColor = $subject == 'neighboring' ? '#ffffff' : $strokeColor;
                $pointSize = $countyId ? 12 : 4;

                $style = sprintf(
                    'point {size: %s; shape-type: circle; stroke-color: %s; stroke-width: 5; fill-color: %s;}',
                    $pointSize,
                    $strokeColor,
                    $fillColor
                );
                $point = array_fill(0, count($chartData[0]), null);
                $point[0] = $growth;

                // Add values to columns corresponding to growing/warning/etc. categories
                $valueCol = $columns[$status];
                $point[$valueCol] = (float)$ratio;
                $tooltipCol = $valueCol + 1;
                $point[$tooltipCol] = $tooltip;
                $styleCol = $tooltipCol + 1;
                $point[$styleCol] = $style;
                $annotationCol = $styleCol + 1;
                $point[$annotationCol] = $countyId ? $annotation : null;

                $chartData[] = $point;
            }
        }

        return [
            'chartData' => $chartData,
            'colors' => $this->getColors(),
            'maxGrowth' => $this->getMaxValue('growth'),
            'maxRatio' => $this->getMaxValue('ratio'),
            'minGrowth' => $this->getMinValue('growth'),
            'minRatio' => $this->getMinValue('ratio'),
            'rhvs' => $rhvs,
            'stateGrowthValue' => $this->getStateGrowthValue()
        ];
    }

    /**
     * Returns a housing value barometer status (growing, recovering, warning, or distressed) for the specified county
     *
     * @param int $countyId County ID
     * @return string
     */
    public function getStatusForCounty($countyId)
    {
        $results = $this->getResults(false, 'growth', $countyId);
        $growth = array_pop($results);
        $results = $this->getResults(false, 'ratio', $countyId);
        $ratio = array_pop($results);

        return $this->getStatus($growth, $ratio);
    }

    /**
     * Returns arrays of county names, keyed with barometer statuses
     *
     * @return array
     */
    public function getBarometerTableData()
    {
        $tableData = array_combine(
            array_keys($this->getColors()),
            [[], [], [], []]
        );
        $counties = $this->Counties->find()
            ->select(['id', 'name', 'simplified'])
            ->orderAsc('name')
            ->all();
        foreach ($counties as $county) {
            $ratios = $this->getResults(false, 'ratio', $county->id);
            $growths = $this->getResults(false, 'growth', $county->id);
            $ratio = array_values($ratios)[0];
            $growth = array_values($growths)[0];
            $status = $this->getStatus($growth, $ratio);
            $tableData[$status][] = $county;
        }

        return $tableData;
    }
}
