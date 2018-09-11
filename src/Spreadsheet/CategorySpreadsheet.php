<?php
namespace App\Spreadsheet;

use App\Model\Entity\Category;
use App\Model\Table\CategoriesTable;
use Cake\ORM\TableRegistry;

class CategorySpreadsheet
{
    /**
     * Returns a PHPExcel object for a spreadsheet documenting all scores in a given category
     *
     * @param Category $category Parent category of the relevant grade and index categories
     * @param int $year Year to retrieve data for
     * @return \PHPExcel
     * @throws \PHPExcel_Exception
     */
    public function getSpreadsheet(Category $category, $year)
    {
        // Set up file
        $fileTitle = 'Indiana Community Asset Inventory and Rankings - ' . $category->name;
        $spreadsheet = new Spreadsheet();
        $spreadsheet
            ->setMetadataTitle($fileTitle)
            ->setAuthor('Center for Business and Economic Research, Ball State University');

        // Create worksheet for scores, abbreviating to keep it within 31-character limit
        $worksheetTitle = str_replace(':', ' - ', $category->name);
        $worksheetTitle = str_replace('Recreation', 'Rec.', $worksheetTitle);
        $worksheetTitle = str_replace('Government', 'Govt.', $worksheetTitle);
        $columnTitles = [
            'County',
            'Grade',
            'Index'
        ];
        $spreadsheet
            ->setColumnTitles($columnTitles)
            ->writeSheetTitle('Scores')
            ->nextRow()
            ->setActiveSheetTitle($worksheetTitle)
            ->writeRow($columnTitles)
            ->styleRow([
                'alignment' => [
                    'horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                    'vertical' => \PHPExcel_Style_Alignment::VERTICAL_CENTER
                ],
                'borders' => [
                    'outline' => ['style' => \PHPExcel_Style_Border::BORDER_THIN]
                ],
                'font' => ['bold' => true]
            ])
            ->nextRow();
        /** @var CategoriesTable $categoriesTable */
        $categoriesTable = TableRegistry::getTableLocator()->get('Categories');
        $scores = $categoriesTable->getScores($category->id, $year);
        $countiesTable = TableRegistry::getTableLocator()->get('Counties');
        $counties = $countiesTable->find()
            ->orderAsc('name')
            ->all();
        foreach ($counties as $county) {
            $spreadsheet
                ->writeRow([
                    $county->name,
                    $scores['grade'][$county->id],
                    $scores['index'][$county->id]
                ])
                ->styleRow([
                    'borders' => [
                        'right' => ['style' => \PHPExcel_Style_Border::BORDER_THIN]
                    ],
                    'font' => ['bold' => true]
                ], 0, 0)
                ->nextRow();
        }

        // Create worksheet for sources
        // (Note: Since this is in a second worksheet, it will not be included in CSV files)
        $spreadsheet
            ->newSheet('Sources')
            ->setColumnTitles([''])
            ->writeSheetTitle('Sources')
            ->setActiveSheetTitle('Sources')
            ->nextRow();
        foreach ($category->sources as $source) {
            $spreadsheet
                ->writeRow([$source->name])
                ->nextRow();
        }


        $spreadsheet
            ->setCellWidth()
            ->selectFirstSheet();

        return $spreadsheet->get();
    }
}
