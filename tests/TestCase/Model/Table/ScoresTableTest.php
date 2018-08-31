<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ScoresTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ScoresTable Test Case
 */
class ScoresTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\ScoresTable
     */
    public $Scores;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.scores',
        'app.counties',
        'app.states',
        'app.categories',
        'app.sources',
        'app.categories_sources'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('Scores') ? [] : ['className' => ScoresTable::class];
        $this->Scores = TableRegistry::get('Scores', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Scores);

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
