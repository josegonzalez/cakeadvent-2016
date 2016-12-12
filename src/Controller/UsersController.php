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
     * A list of actions where the CrudView.View
     * listener should be enabled. If an action is
     * in this list but `isAdmin` is false, the
     * action will still be rendered via CrudView.View
     *
     * @var array
     */
    protected $adminActions = ['login'];

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

        $this->Crud->addListener('Users', 'App\Listener\UsersListener', ['mailer' => 'User']);
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
        if (in_array($this->request->param('action'), ['edit', 'logout'])) {
            return true;
        }
        return parent::isAuthorized($user);
    }
}
