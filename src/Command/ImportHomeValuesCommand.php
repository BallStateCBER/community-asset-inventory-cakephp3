<?php
namespace App\Command;

use App\Model\Entity\County;
use App\Model\Entity\RelativeHomeValue;
use App\Model\Table\CountiesTable;
use App\Model\Table\RelativeHomeValuesTable;
use Cake\Console\Arguments;
use Cake\Console\ConsoleIo;
use Cake\ORM\TableRegistry;
use DataCenter\Command\CommonCommand;

/**
 * Class ImportHomeValuesCommand
 * @package App\Command
 * @property array $data
 * @property array $dataHeaders
 * @property CountiesTable $countiesTable
 * @property int $addCount
 * @property int $ignoreCount
 * @property int $updateCount
 * @property int $year
 * @property RelativeHomeValue[] $rhvs
 * @property RelativeHomeValuesTable $rhvsTable
 */
class ImportHomeValuesCommand extends CommonCommand
{
    private $addCount = 0;
    private $countiesTable;
    private $data;
    private $dataHeaders = [
        'fips',
        'countyName',
        'countyStateRatio', // County_to_state_ratio_for_2017
        'growthChange', // Growth_change_7yrs_countylevel_2010to2017
        'spatialCountyStateRatio', // Spatial_county_to_state_ratio_2017
        'spatialGrowthChange' // Spatial_Growth_change_7yrs_countylevel_2010o2017
    ];
    private $ignoreCount = 0;
    private $rhvs;
    private $rhvsTable;
    private $updateCount = 0;
    private $year;

    /**
     * Sets class properties
     *
     * @param Arguments $args Arguments
     * @param ConsoleIo $io Console IO object
     * @return void
     */
    public function execute(Arguments $args, ConsoleIo $io)
    {
        parent::execute($args, $io);
        $this->countiesTable = TableRegistry::getTableLocator()->get('Counties');
        $this->rhvsTable = TableRegistry::getTableLocator()->get('RelativeHomeValues');

        $this->getData();
        $this->prepareImport();

        if (!$this->getConfirmation('Continue?')) {
            return;
        }

        $this->processImport();
    }

    /**
     * Populates $this->rhvs with entities to be saved to the database
     *
     * @return void
     */
    private function prepareImport()
    {
        $this->rhvs = [];
        $headers = array_slice($this->dataHeaders, 2);

        $this->io->out('Preparing import...');
        foreach ($this->data as $row) {
            /** @var County $county */
            $county = $this->countiesTable->find()->where(['fips' => $row['fips']])->first();
            if (!$county) {
                $this->io->error('County with FIPS code ' . $row['fips'] . ' not found.');
                $this->abort();
            }

            foreach ($headers as $header) {
                $entityData = [
                    'county_id' => $county->id,
                    'is_neighboring' => stripos($header, 'spatial') !== false,
                    'is_ratio' => stripos($header, 'ratio') !== false,
                    'is_growth' => stripos($header, 'growth') !== false,
                    'year' => $this->year,
                    'value' => (float)$row[$header]
                ];
                if ($entityData['is_ratio'] && $entityData['is_growth']) {
                    $this->io->error('Error: RHV for ' . $county->name . ' cannot be both ratio and growth');
                    $this->abort();
                }

                // Already imported (ignore)
                if ($this->rhvsTable->exists($entityData)) {
                    $this->ignoreCount++;
                    continue;
                }

                // Imported, but with a different value (update)
                $entityDataWithoutValue = $entityData;
                unset($entityDataWithoutValue['value']);
                if ($this->rhvsTable->exists($entityDataWithoutValue)) {
                    /** @var RelativeHomeValue $rhv */
                    $rhv = $this->rhvsTable->find()->where($entityDataWithoutValue)->first();
                    $this->rhvsTable->patchEntity($rhv, ['value' => $entityData['value']]);
                    $this->rhvs[] = $rhv;
                    $this->updateCount++;
                    continue;
                }

                // Not imported yet (add)
                $this->rhvs[] = $this->rhvsTable->newEntity($entityData);
                $this->addCount++;
            }
        }
        $this->io->out(' - Done');
        $actionsCounts = [
            'added' => 'addCount',
            'updated' => 'updateCount',
            'ignored' => 'ignoreCount'
        ];
        foreach ($actionsCounts as $action => $countProperty) {
            if ($this->$countProperty) {
                $this->io->out(sprintf(
                    ' - %s %s will be %s',
                    $this->$countProperty,
                    __n('record', 'records', $this->$countProperty),
                    $action
                ));
            }
        }
    }

    /**
     * Processes updates and additions to the database
     *
     * @return void
     */
    private function processImport()
    {
        $this->io->out('Updating database...');
        foreach ($this->rhvs as $rhv) {
            if (!$this->rhvsTable->save($rhv)) {
                $this->io->error("Error saving RHV with this data:\n" . print_r($rhv->toArray(), true));
                $this->io->out('Details:');
                $this->io->out(print_r($rhv->getErrors(), true));
                $this->abort();
            }
        }
        $this->io->out(' - Done');
    }

    /**
     * Returns an array of data from an inelegantly copied CSV file
     *
     * Source: "Housing Barometer work in progress_10&17data_V2.xlsx"
     *
     * @return void
     */
    private function getData()
    {
        $this->io->out('Getting data...');

        $this->year = 2017;
        /*
         * 2017 State data:
         * x-point of intersection: 0.084
         * y-point of intersection: 1.000
         */

        $data = '
            18001,Adams ,0.863,0.202,0.822,0.166,
            18003,Allen ,0.930,0.129,0.920,0.156,
            18005,Bartholomew,1.112,0.121,1.050,0.144,
            18007,Benton ,0.606,0.050,0.855,0.080,
            18009,Blackford,0.501,0.070,0.694,0.100,
            18011,Boone ,1.624,0.158,0.993,0.114,
            18013,Brown ,1.466,0.211,0.912,0.111,
            18015,Carroll ,0.895,0.338,0.632,0.102,
            18017,Cass ,0.598,0.148,0.784,0.183,
            18019,Clark,0.967,0.087,0.948,0.181,
            18021,Clay ,0.782,0.214,0.702,0.100,
            18023,Clinton ,0.746,0.181,1.142,0.171,
            18025,Crawford ,0.614,0.145,0.731,0.160,
            18027,Daviess ,0.912,0.359,0.601,0.107,
            18029,Dearborn ,1.242,0.072,1.081,0.090,
            18031,Decatur ,0.933,0.122,0.938,0.094,
            18033,DeKalb ,0.967,0.165,1.064,0.172,
            18035,Delaware,0.667,0.012,0.575,0.091,
            18037,Dubois,1.162,0.194,0.783,0.184,
            18039,Elkhart ,1.053,0.120,0.857,0.125,
            18041,Fayette ,0.523,0.138,0.623,0.055,
            18043,Floyd ,1.305,0.128,0.937,0.141,
            18045,Fountain,0.616,0.109,0.643,0.104,
            18047,Franklin ,1.024,0.049,0.856,0.087,
            18049,Fulton ,0.741,0.163,0.806,0.152,
            18051,Gibson ,0.849,0.192,0.611,0.087,
            18053,Grant ,0.572,0.019,0.714,0.106,
            18055,Greene ,0.632,0.116,0.781,0.161,
            18057,Hamilton,1.895,0.159,1.016,0.115,
            18059,Hancock,1.257,0.092,0.961,0.090,
            18061,Harrison,1.057,0.119,0.901,0.164,
            18063,Hendricks,1.384,0.143,0.916,0.111,
            18065,Henry,0.619,0.093,0.713,0.063,
            18067,Howard,0.717,0.109,0.720,0.177,
            18069,Huntington ,0.734,0.086,0.730,0.111,
            18071,Jackson ,0.906,0.154,1.004,0.165,
            18073,Jasper,1.224,0.089,1.000,0.081,
            18075,Jay ,0.590,0.269,0.510,0.069,
            18077,Jefferson ,0.928,0.197,0.713,0.099,
            18079,Jennings,0.859,0.169,0.936,0.139,
            18081,Johnson,1.203,0.121,0.930,0.102,
            18083,Knox ,0.607,0.164,0.592,0.147,
            18085,Kosciusko ,1.153,0.091,0.928,0.164,
            18087,LaGrange,1.135,0.156,1.053,0.172,
            18089,Lake ,1.062,0.067,1.196,0.091,
            18091,La Porte,1.450,0.007,1.086,0.054,
            18093,Lawrence ,0.696,0.098,0.817,0.144,
            18095,Madison,0.645,0.033,0.982,0.086,
            18097,Marion ,0.940,0.086,1.347,0.122,
            18099,Marshall ,1.054,0.151,0.892,0.111,
            18101,Martin,0.606,0.064,0.680,0.153,
            18103,Miami ,0.626,0.233,0.526,0.088,
            18105,Monroe,1.338,0.161,0.931,0.135,
            18107,Montgomery ,0.883,0.220,1.017,0.131,
            18109,Morgan ,1.133,0.093,1.150,0.145,
            18111,Newton,0.938,0.166,0.964,0.069,
            18113,Noble ,0.945,0.226,1.078,0.142,
            18115,Ohio,1.253,0.161,0.999,0.101,
            18117,Orange ,0.633,0.153,0.616,0.100,
            18119,Owen ,0.754,0.139,0.777,0.117,
            18121,Parke ,0.687,0.099,0.737,0.138,
            18123,Perry ,0.803,0.334,0.893,0.191,
            18125,Pike ,0.565,0.069,0.706,0.182,
            18127,Porter ,1.425,0.019,1.132,0.062,
            18129,Posey ,0.998,0.083,0.866,0.155,
            18131,Pulaski,0.711,0.184,0.904,0.118,
            18133,Putnam ,0.965,0.155,0.937,0.151,
            18135,Randolph ,0.520,0.061,0.629,0.101,
            18137,Ripley,0.966,0.059,1.004,0.135,
            18139,Rush ,0.738,0.077,0.881,0.097,
            18141,St Joseph,0.905,0.024,1.087,0.090,
            18143,Scott ,0.772,0.180,0.732,0.121,
            18145,Shelby ,0.931,0.089,1.030,0.103,
            18147,Spencer,0.903,0.234,1.063,0.213,
            18149,Starke ,0.791,0.084,1.073,0.091,
            18151,Steuben ,1.247,0.176,1.015,0.183,
            18153,Sullivan ,0.550,0.087,0.662,0.124,
            18155,Switzerland ,0.789,0.171,1.049,0.139,
            18157,Tippecanoe ,1.097,0.072,0.884,0.150,
            18159,Tipton ,0.882,0.141,0.800,0.096,
            18161,Union,0.734,0.056,0.728,0.072,
            18163,Vanderburgh ,0.883,0.119,1.024,0.129,
            18165,Vermillion,0.550,0.131,0.654,0.071,
            18167,Vigo,0.624,0.003,0.642,0.133,
            18169,Wabash ,0.708,0.170,0.815,0.125,
            18171,Warren,0.690,0.072,0.717,0.090,
            18173,Warrick,1.224,0.111,0.696,0.138,
            18175,Washington ,0.785,0.218,0.869,0.133,
            18177,Wayne ,0.638,0.029,0.599,0.087,
            18179,Wells,0.946,0.101,0.698,0.129,
            18181,White,1.014,0.072,0.855,0.147,
            18183,Whitley ,1.065,0.157,0.752,0.107,
        ';

        $this->data = [];
        foreach (explode("\n", $data) as $rowNum => $row) {
            $row = trim($row);
            if (!$row) {
                continue;
            }

            $cells = explode(',', $row);
            foreach ($cells as $k => $cell) {
                $cell = trim($cell);
                if (isset($this->dataHeaders[$k])) {
                    $header = $this->dataHeaders[$k];
                    $this->data[$rowNum][$header] = $cell;
                }
            }
        }

        $this->io->out(' - Done');
    }
}
