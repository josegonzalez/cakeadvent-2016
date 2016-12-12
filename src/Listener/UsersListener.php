<?php
namespace App\Listener;

use Cake\Event\Event;
use Cake\Mailer\MailerAwareTrait;
use Cake\ORM\TableRegistry;
use Crud\Listener\BaseListener;

/**
 * Users Listener
 */
class UsersListener extends BaseListener
{
    use MailerAwareTrait;

    /**
     * Default config for this object.
     *
     * @var array
     */
    protected $_defaultConfig = [
        'mailer' => 'User',
    ];

    /**
     * Callbacks definition
     *
     * @return array
     */
    public function implementedEvents()
    {
        return [
            'Crud.afterForgotPassword' => 'afterForgotPassword',
            'Crud.beforeHandle' => 'beforeHandle',
            'Crud.beforeRender' => 'beforeRender',
            'Crud.beforeSave' => 'beforeSave',
            'Crud.verifyToken' => 'verifyToken',
        ];
    }

    /**
     * After Forgot Password
     *
     * @param \Cake\Event\Event $event Event
     * @return void
     */
    public function afterForgotPassword(Event $event)
    {
        if (!$event->subject->success) {
            return;
        }

        $table = TableRegistry::get($this->_controller()->modelClass);
        $token = $table->tokenize($event->subject->entity->id);

        if ($this->config('mailer')) {
            $this->getMailer($this->config('mailer'))->send('forgotPassword', [
                $event->subject->entity->toArray(),
                $token,
            ]);
        }
    }

    /**
     * Before Verify
     *
     * @param \Cake\Event\Event $event Event
     * @return void
     */
    public function verifyToken(Event $event)
    {
        $event->subject->verified = TableRegistry::get('Muffin/Tokenize.Tokens')
            ->verify($event->subject->token);
    }

    /**
     * Before Handle
     *
     * @param \Cake\Event\Event $event Event
     * @return void
     */
    public function beforeHandle(Event $event)
    {
        if ($event->subject->action === 'edit') {
            $this->beforeHandleEdit($event);

            return;
        }
        if ($event->subject->action === 'login') {
            $this->beforeHandleLogin($event);

            return;
        }
        if ($event->subject->action === 'forgotPassword') {
            $this->beforeHandleForgotPassword($event);

            return;
        }
        if ($event->subject->action === 'resetPassword') {
            $this->beforeHandleResetPassword($event);

            return;
        }
    }

    /**
     * Before Handle Edit Action
     *
     * @param \Cake\Event\Event $event Event
     * @return void
     */
    public function beforeHandleEdit(Event $event)
    {
        $userId = $this->_controller()->Auth->user('id');
        $event->subject->args = [$userId];

        $this->_action()->saveOptions(['validate' => 'account']);
        $this->_action()->config('scaffold.page_title', 'Profile');
        $this->_action()->config('scaffold.disable_extra_buttons', true);
        $this->_action()->config('scaffold.viewblocks', [
            'actions' => ['' => 'text'],
        ]);
        $this->_action()->config('scaffold.fields', [
            'email',
            'password' => [
                'required' => false,
            ],
            'confirm_password' => [
                'type' => 'password',
            ],
            'avatar' => [
                'type' => 'file'
            ],
        ]);
    }

    /**
     * Before Handle ForgotPassword Action
     *
     * @param \Cake\Event\Event $event Event
     * @return void
     */
    public function beforeHandleForgotPassword(Event $event)
    {
        $this->_controller()->set([
            'viewVar' => 'forgotPassword',
            'forgotPassword' => null,
        ]);
        $this->_controller()->viewBuilder()->template('add');
        $this->_action()->config('scaffold.page_title', 'Forgot Password?');
        $this->_action()->config('scaffold.fields', [
            'email',
        ]);
        $this->_action()->config('scaffold.viewblocks', [
            'actions' => ['' => 'text'],
        ]);
        $this->_action()->config('scaffold.sidebar_navigation', false);
        $this->_action()->config('scaffold.disable_extra_buttons', true);
        $this->_action()->config('scaffold.submit_button_text', 'Send Password Reset Email');
    }

    /**
     * Before Handle Login Action
     *
     * @param \Cake\Event\Event $event Event
     * @return void
     */
    public function beforeHandleLogin(Event $event)
    {
        $this->_controller()->set([
            'viewVar' => 'login',
            'login' => null,
        ]);
        $this->_controller()->viewBuilder()->template('add');
        $this->_action()->config('scaffold.page_title', 'Login');
        $this->_action()->config('scaffold.fields', [
            'email',
            'password',
        ]);
        $this->_action()->config('scaffold.viewblocks', [
            'actions' => ['' => 'text'],
        ]);
        $this->_action()->config('scaffold.sidebar_navigation', false);
        $this->_action()->config('scaffold.disable_extra_buttons', true);
        $this->_action()->config('scaffold.submit_button_text', 'Login');
    }

    /**
     * Before Handle ResetPassword Action
     *
     * @param \Cake\Event\Event $event Event
     * @return void
     */
    public function beforeHandleResetPassword(Event $event)
    {
        $this->_controller()->set([
            'viewVar' => 'resetPassword',
            'resetPassword' => null,
        ]);
        $this->_controller()->viewBuilder()->template('add');
        $this->_action()->config('scaffold.page_title', 'Enter a new password to reset your account');
        $this->_action()->config('scaffold.fields', [
            'password',
        ]);
        $this->_action()->config('scaffold.viewblocks', [
            'actions' => ['' => 'text'],
        ]);
        $this->_action()->config('scaffold.sidebar_navigation', false);
        $this->_action()->config('scaffold.disable_extra_buttons', true);
        $this->_action()->config('scaffold.submit_button_text', 'Reset Password');
    }

    /**
     * Before Render
     *
     * @param \Cake\Event\Event $event Event
     * @return void
     */
    public function beforeRender(Event $event)
    {
        if ($this->_request()->action === 'edit') {
            $this->beforeRenderEdit($event);

            return;
        }
    }

    /**
     * Before Render Edit Action
     *
     * @param \Cake\Event\Event $event Event
     * @return void
     */
    public function beforeRenderEdit(Event $event)
    {
        $event->subject->entity->unsetProperty('password');
    }

    /**
     * Before Save
     *
     * @param \Cake\Event\Event $event Event
     * @return void
     */
    public function beforeSave(Event $event)
    {
        if ($this->_request()->action === 'edit') {
            $this->beforeSaveEdit($event);

            return;
        }
    }

    /**
     * Before Render Edit Action
     *
     * @param \Cake\Event\Event $event Event
     * @return void
     */
    public function beforeSaveEdit(Event $event)
    {
        if ($event->subject->entity->confirm_password === '') {
            $event->subject->entity->unsetProperty('password');
            $event->subject->entity->dirty('password', false);
        }
    }
}
