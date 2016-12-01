<?php
namespace App\Test\TestCase\Controller;

use App\Controller\PostAttributesController;
use Cake\TestSuite\IntegrationTestCase;

/**
 * App\Controller\PostAttributesController Test Case
 */
class PostAttributesControllerTest extends IntegrationTestCase
{

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
     * Test initial setup
     *
     * @return void
     */
    public function testInitialization()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
