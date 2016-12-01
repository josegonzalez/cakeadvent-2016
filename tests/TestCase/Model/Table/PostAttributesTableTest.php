<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\PostAttributesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\PostAttributesTable Test Case
 */
class PostAttributesTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\PostAttributesTable
     */
    public $PostAttributes;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.post_attributes',
        'app.posts',
        'app.users'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('PostAttributes') ? [] : ['className' => 'App\Model\Table\PostAttributesTable'];
        $this->PostAttributes = TableRegistry::get('PostAttributes', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->PostAttributes);

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
