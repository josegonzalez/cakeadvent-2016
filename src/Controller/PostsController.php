<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;
use CrudView\Menu\MenuDivider;
use CrudView\Menu\MenuDropdown;
use CrudView\Menu\MenuItem;

/**
 * Posts Controller
 *
 * @property \App\Model\Table\PostsTable $Posts
 */
class PostsController extends AppController
{
    /**
     * A list of actions where the CrudView.View
     * listener should be enabled. If an action is
     * in this list but `isAdmin` is false, the
     * action will still be rendered via CrudView.View
     *
     * @var array
     */
    protected $adminActions = ['index', 'add', 'edit', 'delete'];

    /**
     * A list of actions that should be allowed for
     * authenticated users
     *
     * @var array
     */
    protected $allowedActions = [
        'index',
        'add',
        'edit',
        'delete',
    ];

    /**
     * Initialization hook method.
     *
     * Use this method to add common initialization code like loading components.
     *
     * e.g. `$this->loadComponent('Security');`
     *
     * @return void
     */
    public function initialize()
    {
        parent::initialize();

        $this->Crud->addListener('Posts', 'App\Listener\PostsListener');
        $this->Crud->mapAction('home', 'Crud.Index');
        $this->Auth->allow(['home', 'view']);
    }
}
