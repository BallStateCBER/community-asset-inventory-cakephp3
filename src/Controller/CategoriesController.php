<?php
namespace App\Controller;

use App\Model\Entity\Category;
use App\Model\Table\CategoriesTable;
use App\Model\Table\CountiesTable;
use Cake\Datasource\ResultSetInterface;
use Cake\Http\Response;

/**
 * Categories Controller
 *
 * @property CategoriesTable $Categories
 * @property CountiesTable $Counties
 * @method Category[]|ResultSetInterface paginate($object = null, array $settings = [])
 */
class CategoriesController extends AppController
{
    /**
     * View method
     *
     * @param string $slug Category slug
     * @return Response|null
     */
    public function view($slug)
    {
        $year = 2012;

        /** @var Category $parentCategory */
        $parentCategory = $this->Categories
            ->find()
            ->where(['slug' => $slug])
            ->contain(['Sources'])
            ->first();

        if (!$parentCategory) {
            $this->Flash->error('Sorry, that page wasn\'t found.');

            return $this->redirect([
                'controller' => 'Pages',
                'action' => 'home'
            ]);
        }

        $scores = $this->Categories->getScores($parentCategory->id, $year);

        $this->loadModel('Counties');
        $this->set([
            'parentCategory' => $parentCategory,
            'counties' => $this->Counties->find()->all(),
            'titleForLayout' => $parentCategory->name,
            'scores' => $scores,
            'urlParams' => [
                'controller' => 'reports',
                'action' => 'download',
                'var_id' => $parentCategory->id
            ],
            'downloadOptions' => [
                [
                    'displayed_type' => 'CSV',
                    'icon' => 'icons/document-excel-csv.png',
                    'type_param' => 'csv'
                ],
                [
                    'displayed_type' => 'Excel 5.0',
                    'icon' => 'icons/document-excel-table.png',
                    'type_param' => 'excel5'
                ],
                [
                    'displayed_type' => 'Excel 2007',
                    'icon' => 'icons/document-excel-table.png',
                    'type_param' => 'excel2007'
                ]
            ]
        ]);

        return null;
    }
}
