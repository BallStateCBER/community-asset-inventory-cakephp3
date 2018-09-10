<?php
namespace App\Controller;

use App\Model\Entity\Category;
use App\Model\Table\CategoriesTable;
use App\Model\Table\CountiesTable;
use App\Spreadsheet\CategorySpreadsheet;
use Cake\Datasource\ResultSetInterface;
use Cake\Http\Exception\BadRequestException;
use Cake\Http\Response;
use Cake\Utility\Hash;

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

        $scores = $this->Categories->getScores($parentCategory->id, $this->dataYear);

        $this->loadModel('Counties');
        $this->set([
            'parentCategory' => $parentCategory,
            'counties' => $this->Counties->find()->all(),
            'titleForLayout' => $parentCategory->name,
            'scores' => $scores,
            'urlParams' => [
                'controller' => 'Categories',
                'action' => 'download',
                $parentCategory->id
            ],
            'downloadOptions' => $this->getDownloadOptions()
        ]);

        return null;
    }

    /**
     * Renders a spreadsheet download
     *
     * @param int|null $categoryId Parent category ID
     * @param string|null $fileType Either excel2007 or csv
     * @return void
     * @throws \PHPExcel_Exception
     */
    public function download($categoryId = null, $fileType = null)
    {
        // Validate parameters
        if (!$categoryId) {
            throw new BadRequestException('No data category specified');
        }
        if (!$fileType) {
            throw new BadRequestException('No file type specified');
        }
        $isValidFileType = in_array(
            $fileType,
            Hash::extract($this->getDownloadOptions(), '{n}.type_param')
        );
        if (!$isValidFileType) {
            throw new BadRequestException('Invalid file type specified');
        }

        // Collect data
        /** @var Category $parentCategory */
        $parentCategory = $this->Categories
            ->find()
            ->where(['id' => $categoryId])
            ->contain(['Sources'])
            ->first();

        $writerTypes = [
            'excel2007' => 'Excel2007',
            'csv' => 'CSV'
        ];
        $this->viewBuilder()->setLayout('spreadsheet');
        $this->set([
            'spreadsheet' => (new CategorySpreadsheet())->getSpreadsheet($parentCategory, $this->dataYear),
            'writerType' => $writerTypes[$fileType]
        ]);
        $response = $this->response;

        $extensions = [
            'excel2007' => 'xlsx',
            'csv' => 'csv'
        ];
        $extension = $extensions[$fileType];
        $response = $response->withType($extension);
        $filename = sprintf(
            'CAIR - %s.%s',
            str_replace(':', ' - ', $parentCategory->name),
            $extension
        );
        $response = $response->withDownload($filename);
        $this->response = $response;
    }

    /**
     * Returns information about spreadsheet download options
     *
     * @return array
     */
    private function getDownloadOptions()
    {
        return [
            [
                'displayed_type' => 'CSV',
                'icon' => 'icons/document-excel-csv.png',
                'type_param' => 'csv'
            ],
            [
                'displayed_type' => 'Excel (XLSX)',
                'icon' => 'icons/document-excel-table.png',
                'type_param' => 'excel2007'
            ]
        ];
    }
}
