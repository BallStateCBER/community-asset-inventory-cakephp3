<?php
namespace App\Controller;

use App\Model\Entity\County;
use App\Model\Table\CountiesTable;
use Cake\ORM\TableRegistry;

/**
 * Class CountiesController
 * @package App\Controller
 * @property CountiesTable $Counties
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
        $categories = $categoriesTable->find('threaded')
            ->contain(['Scores'])
            ->orderAsc('name')
            ->all();
        /** @var CountiesTable $countiesTable */
        $countiesTable = TableRegistry::getTableLocator()->get('Counties');
        $this->set([
            'county' => $county,
            'categories' => $categories,
            'scores' => $countiesTable->getScores($county->id, $this->dataYear),
            'titleForLayout' => $county->name
        ]);
    }
}
