<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Posts Controller
 *
 * @property \App\Model\Table\PostsTable $Posts
 */
class PostsController extends AppController
{
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
        $this->Crud->mapAction('home', 'Crud.Index');
        $this->Auth->allow(['home']);
    }

    public function home()
    {
        $this->Crud->action()->view('index');
        return $this->Crud->execute();
    }
}
