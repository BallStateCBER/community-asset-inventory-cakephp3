<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\RelativeHomeValuesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\RelativeHomeValuesTable Test Case
 */
class RelativeHomeValuesTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\RelativeHomeValuesTable
     */
    public $RelativeHomeValues;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.relative_home_values',
        'app.counties'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('RelativeHomeValues') ? [] : ['className' => RelativeHomeValuesTable::class];
        $this->RelativeHomeValues = TableRegistry::getTableLocator()->get('RelativeHomeValues', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->RelativeHomeValues);

        parent::tearDown();
    }

    /**
     * Test initialize method
     *
     * @return void
     */
    public function testInitialize()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test validationDefault method
     *
     * @return void
     */
    public function testValidationDefault()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     *
     * @return void
     */
    public function testBuildRules()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
