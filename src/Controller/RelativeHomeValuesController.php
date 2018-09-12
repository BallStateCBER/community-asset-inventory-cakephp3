<?php
namespace App\Controller;

use App\Model\Table\RelativeHomeValuesTable;
use Cake\ORM\Query;
use Cake\Utility\Hash;

/**
 * Class RelativeHomeValuesController
 *
 * @package App\Controller
 * @property RelativeHomeValuesTable $RelativeHomeValues
 *
 */
class RelativeHomeValuesController extends AppController
{
    /**
     * Index method
     *
     * @return void
     */
    public function index()
    {
        // Relative home values
        $rhvs = [];

        foreach (['ratio', 'growth'] as $type) {
            $rhvs['counties'][$type] = $this->getResults(false, $type);
            $rhvs['neighboring'][$type] = $this->getResults(true, $type);
        }

        $chartData = [
            [
                'Growth',
                'Ratio',
                [
                    'type' => 'string',
                    'role' => 'tooltip'
                ],
                [
                    'type' => 'string',
                    'role' => 'style'
                ]
            ],
        ];
        $countyNames = array_keys($rhvs['counties']['ratio']);
        foreach ($countyNames as $countyName) {
            foreach (['counties', 'neighboring'] as $subject) {
                $growth = $rhvs[$subject]['growth'][$countyName];
                $ratio = $rhvs[$subject]['ratio'][$countyName];
                $color = $this->getColor($growth, $ratio);
                $chartData[] = [
                    $growth,
                    $ratio,
                    $subject == 'counties'
                        ? "$countyName County"
                        : "Counties neighboring $countyName County",
                    sprintf(
                        'point {size: 4; shape-type: circle; stroke-color: %s; fill-color: %s;}',
                        $color,
                        $subject == 'neighboring'
                            ? '#ffffff'
                            : $color
                    )
                ];
            }
        }

        $this->set([
            'chartData' => $chartData,
            'maxGrowth' => $this->RelativeHomeValues->getMaxValue('growth'),
            'maxRatio' => $this->RelativeHomeValues->getMaxValue('ratio'),
            'minGrowth' => $this->RelativeHomeValues->getMinValue('growth'),
            'minRatio' => $this->RelativeHomeValues->getMinValue('ratio'),
            'rhvs' => $rhvs,
            'titleForLayout' => 'Relative Home Values'
        ]);
    }

    /**
     * Returns an array of RelativeHomeValue values, keyed by county names
     *
     * @param bool $isNeighboring True if value is only for neighboring counties of each county
     * @param string $type Either ratio or growth
     * @return array
     */
    private function getResults($isNeighboring, $type)
    {
        $year = 2017;
        $results = $this->RelativeHomeValues->find()
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
            ])
            ->toArray();

        return Hash::combine($results, '{n}.county.name', '{n}.value');
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
        $stateGrowth = 0.084;

        if ($growth <= $stateGrowth) {
            return $ratio >= 1
                ? $colors['warning']
                : $colors['bad'];
        }

        return $ratio >= 1
            ? $colors['ideal']
            : $colors['growth'];
    }


    /**
     * Returns an array of hex codes for colors used in scatter plot
     *
     * @return array
     */
    private function getColors()
    {
        return [
            'warning' => '#ff9900',
            'bad' => '#dc3912',
            'ideal' => '#109618',
            'growth' => '#3366cc'
        ];
    }
}
