<?php
namespace App\Controller;

use App\Model\Table\RelativeHomeValuesTable;

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
        $this->set($this->RelativeHomeValues->getVarsForScatterPlot());
        $this->set([
            'titleForLayout' => 'Housing Value Barometer'
        ]);
    }
}
