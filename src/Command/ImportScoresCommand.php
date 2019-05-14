<?php
namespace App\Command;

use App\Model\Entity\County;
use App\Model\Entity\Score;
use App\Model\Table\ScoresTable;
use Cake\Console\Arguments;
use Cake\Console\Command;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;
use Cake\ORM\TableRegistry;

/**
 * ImportScores command.
 *
 * @property ConsoleIo $io
 * @property ScoresTable $scoresTable
 * @property int $year
 */
class ImportScoresCommand extends Command
{
    private $io;
    private $overwrite;
    private $scoresTable;
    private $year;

    /**
     * Hook method for defining this command's option parser.
     *
     * @see https://book.cakephp.org/3.0/en/console-and-shells/commands.html#defining-arguments-and-options
     *
     * @param ConsoleOptionParser $parser The parser to be defined
     * @return ConsoleOptionParser The built parser.
     */
    public function buildOptionParser(ConsoleOptionParser $parser)
    {
        $parser = parent::buildOptionParser($parser);

        return $parser;
    }

    /**
     * Processes a data file and imports it into the 'scores' DB table
     *
     * @param Arguments $args The command arguments.
     * @param ConsoleIo $io The console io
     * @return void
     */
    public function execute(Arguments $args, ConsoleIo $io)
    {
        $this->io = $io;
        $filename = '2018 CAIR scorecard.txt';
        $filePath = TMP . $filename;
        $this->year = (int)substr($filename, 0, 4);
        if (!is_numeric($this->year)) {
            $io->err("Year $this->year is not numeric");
            $this->abort();
        }

        $choice = $io->askChoice("Import file $filename for year $this->year?", ['y', 'n'], 'y');
        if ($choice != 'y') {
            return;
        }

        $this->overwrite = $io->askChoice('Overwrite existing values in the database?', ['y', 'n'], 'y') == 'y';

        $fh = fopen($filePath, 'r');
        $headerRowCount = 1;
        if (!$fh) {
            $io->err('Cannot open data file');
            $this->abort();
        }

        // Process each row of the data file
        $countiesTable = TableRegistry::getTableLocator()->get('Counties');
        $categoriesTable = TableRegistry::getTableLocator()->get('Categories');
        $this->scoresTable = TableRegistry::getTableLocator()->get('Scores');
        $categories = [];
        while (!feof($fh) && $row = fgets($fh)) {
            // Skip header rows
            if ($headerRowCount > 0) {
                $headerRowCount--;
                continue;
            }

            // Skip blank rows
            if (trim($row) == '') {
                continue;
            }

            $fields = explode("\t", $row);
            $fips = trim($fields[0]);
            /** @var County $county */
            $county = $countiesTable->find()
                ->where(compact('fips'))
                ->first();
            if (!$county) {
                $io->error("No county found corresponding to FIPS code $fips");
                $this->abort();
            }

            $dataColumns = [    // col num => (name, category ID)
                2 => ['People: Grade', 2],
                3 => ['People: Index', 3],

                5 => ['Human Capital - Education: Grade', 5],
                6 => ['Human Capital - Education: Index', 6],

                8 => ['Government Impact & Economy: Grade', 8],
                9 => ['Government Impact & Economy: Index', 9],

                11 => ['Changeable Amenities (Public): Index', 11],

                12 => ['Relatively Static Amenities (Public): Index', 13],

                14 => ['Recreation (Private): Grade', 15],
                15 => ['Recreation (Private): Index', 16],

                17 => ['Human Capital - Health: Grade', 18],
                18 => ['Human Capital - Health: Index', 19]
            ];
            foreach ($dataColumns as $colNum => $category) {
                list($categoryName, $categoryId) = $category;
                if (!isset($categories[$categoryId])) {
                    $categories[$categoryId] = $categoriesTable->get($categoryId);
                }

                $io->out($county->name . ': ' . $categoryName);
                $value = trim($fields[$colNum]);
                $this->saveScore($categoryId, $county, $value);
            }
        }

        $io->success('Done');
    }

    /**
     * @param $categoryId
     * @param $county
     * @param $value
     * @return void
     */
    private function saveScore($categoryId, $county, $value)
    {
        if ($value === '') {
            $this->io->warning(' -> Score is blank, skipping');

            return;
        }

        $redundantScores = $this->scoresTable->find()
            ->select(['id', 'value'])
            ->where([
                'category_id' => $categoryId,
                'county_id' => $county->id,
                'year' => $this->year
            ])
            ->toArray();

        // Delete extra scores
        $redundantCount = count($redundantScores);
        if ($redundantCount > 1) {
            if ($this->overwrite) {
                $scoresToDelete = array_slice($redundantScores, 1);
                $this->deleteRedundantScores($scoresToDelete);
            } else {
                $this->io->warning(' -> Extra scores should be deleted, but overwriting is disabled');
            }
        }

        $data = [
            'county_id' => $county->id,
            'category_id' => $categoryId,
            'is_state_average' => false,
            'value' => $value,
            'year' => $this->year
        ];

        if ($redundantScores) {
            $existingScore = $redundantScores[0];

            if ($existingScore->value == $value) {
                $this->io->warning(' -> Score already recorded');

                return;
            }

            if (!$this->overwrite) {
                $this->io->warning(' -> Score differs from what\'s in the database, but overwrite is disabled');

                return;
            }

            $this->scoresTable->patchEntity($existingScore, $data);
            if ($this->scoresTable->save($existingScore)) {
                $this->io->success(' -> Updated');

                return;
            }

            $this->io->err(' -> Error updating');
            $this->abort();
        }

        $newScore = $this->scoresTable->newEntity($data);
        if ($this->scoresTable->save($newScore)) {
            $this->io->success(" -> Added");

            return;
        }

        $this->io->err(" -> Error adding");
        $this->abort();
    }

    /**
     * Deletes all of the provided scores
     *
     * @param Score[] $scores Array of score entities
     * @return void
     */
    private function deleteRedundantScores($scores)
    {
        /** @var Score $redundantRow */
        foreach ($scores as $redundantRow) {
            $this->scoresTable->delete($redundantRow);
            $this->io->success(" -> Deleted redundant score #$redundantRow->id in database");
        }
    }
}
