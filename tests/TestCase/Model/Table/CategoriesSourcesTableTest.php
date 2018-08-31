<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\CategoriesSourcesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\CategoriesSourcesTable Test Case
 */
class CategoriesSourcesTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\CategoriesSourcesTable
     */
    public $CategoriesSources;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.categories_sources',
        'app.sources',
        'app.categories',
        'app.scores'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('CategoriesSources') ? [] : ['className' => CategoriesSourcesTable::class];
        $this->CategoriesSources = TableRegistry::get('CategoriesSources', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->CategoriesSources);

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
