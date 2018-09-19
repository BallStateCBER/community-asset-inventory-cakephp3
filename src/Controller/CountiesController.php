<?php
namespace App\Controller;

use App\Model\Entity\County;
use App\Model\Table\CountiesTable;
use App\Model\Table\RelativeHomeValuesTable;
use Cake\ORM\TableRegistry;

/**
 * Class CountiesController
 * @package App\Controller
 * @property CountiesTable $Counties
 * @property RelativeHomeValuesTable $RelativeHomeValues
 */
class CountiesController extends AppController
{
    /**
     * Page for viewing all scores for a given county
     *
     * @param string $countySlug Slugged county name
     * @return void
     */
    public function view($countySlug)
    {
        /** @var County $county */
        $county = $this->Counties->find()
            ->where(['simplified' => $countySlug])
            ->firstOrFail();
        $categoriesTable = TableRegistry::getTableLocator()->get('Categories');
        $lowYear = 2012;
        $highYear = 2018;
        $categories = $categoriesTable->find('threaded')
            ->orderAsc('name')
            ->all();
        /** @var CountiesTable $countiesTable */
        $countiesTable = TableRegistry::getTableLocator()->get('Counties');
        $this->loadModel('RelativeHomeValues');
        $this->set($this->RelativeHomeValues->getVarsForScatterPlot($county->id));
        $this->set([
            'barometerStatus' => $this->RelativeHomeValues->getStatusForCounty($county->id),
            'county' => $county,
            'categories' => $categories,
            'highYear' => $highYear,
            'lowYear' => $lowYear,
            'scores' => $countiesTable->getScores($county->id, [$lowYear, $highYear]),
            'titleForLayout' => $county->name
        ]);
    }
}
