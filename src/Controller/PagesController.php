<?php
/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link      https://cakephp.org CakePHP(tm) Project
 * @since     0.2.9
 * @license   https://opensource.org/licenses/mit-license.php MIT License
 */
namespace App\Controller;

use App\Model\Table\RelativeHomeValuesTable;

/**
 * Pages controller
 *
 * This controller will render views from Template/Pages/
 *
 * @property RelativeHomeValuesTable $RelativeHomeValues
 */
class PagesController extends AppController
{

    /**
     * Displays the home page
     *
     * @return void
     */
    public function home()
    {
        $exampleCountyId = 49; // Marion
        $this->loadModel('RelativeHomeValues');
        $this->set([
            'barometerStatus' => $this->RelativeHomeValues->getStatusForCounty($exampleCountyId),
            'colors' => $this->RelativeHomeValues->getColors()
        ]);
    }

    /**
     * Data sources and methodology page
     *
     * @return void
     */
    public function sources()
    {
        $this->set('titleForLayout', 'Data Sources and Methodology');
    }

    /**
     * Credits page
     *
     * @return void
     */
    public function credits()
    {
        $this->set('titleForLayout', 'Credits');
    }

    /**
     * Frequently asked questions page
     *
     * @return void
     */
    public function faq()
    {
        $this->set('titleForLayout', 'Frequently Asked Questions');
    }
}
