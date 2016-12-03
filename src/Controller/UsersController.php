<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Users Controller
 *
 * @property \App\Model\Table\UsersTable $Users
 */
class UsersController extends AppController
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

        $this->Crud->mapAction('login', 'CrudUsers.Login');
        $this->Crud->mapAction('logout', 'CrudUsers.Logout');
        $this->Crud->mapAction('forgotPassword', 'CrudUsers.ForgotPassword');
        $this->Crud->mapAction('resetPassword', [
            'className' => 'CrudUsers.ResetPassword',
            'findMethod' => 'token',
        ]);
        $this->Crud->mapAction('verify', [
            'className' => 'CrudUsers.Verify',
            'findMethod' => 'token',
        ]);
        $this->Auth->allow(['forgotPassword', 'resetPassword', 'verify']);
    }

    /**
     * Check if the provided user is authorized for the request.
     *
     * @param array|\ArrayAccess|null $user The user to check the authorization of.
     *   If empty the user fetched from storage will be used.
     * @return bool True if $user is authorized, otherwise false
     */
    public function isAuthorized($user = null)
    {
        if ($this->request->param('action') == 'logout') {
            return true;
        }
        return parent::isAuthorized($user);
    }
}
