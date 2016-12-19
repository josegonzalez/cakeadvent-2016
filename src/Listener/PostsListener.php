<?php
namespace App\Listener;

use App\Model\Table\PostsTable;
use App\PostType\AbstractPostType;
use Cake\Core\App;
use Cake\Event\Event;
use Cake\I18n\Time;
use Cake\ORM\TableRegistry;
use Crud\Listener\BaseListener;

/**
 * Posts Listener
 */
class PostsListener extends BaseListener
{
    /**
     * Callbacks definition
     *
     * @return array
     */
    public function implementedEvents()
    {
        return [
            'Crud.beforeHandle' => 'beforeHandle',
            'Crud.beforeRender' => 'beforeRender',
            'Crud.beforeSave' => 'beforeSave',
        ];
    }

    /**
     * Before Handle
     *
     * @param \Cake\Event\Event $event Event
     * @return void
     */
    public function beforeHandle(Event $event)
    {
        if ($this->_request()->action === 'index') {
            $this->beforeHandleIndex($event);

            return;
        }

        if ($this->_request()->action === 'home') {
            $this->beforeHandleHome($event);

            return;
        }
    }

    /**
     * Before Render
     *
     * @param \Cake\Event\Event $event Event
     * @return void
     */
    public function beforeRender(Event $event)
    {
        if ($this->_request()->action === 'add') {
            $this->beforeRenderAdd($event);

            return;
        }

        if ($this->_request()->action === 'edit') {
            $this->beforeRenderEdit($event);

            return;
        }
    }

    /**
     * Before Save
     *
     * @param \Cake\Event\Event $event Event
     * @return void
     */
    public function beforeSave(Event $event)
    {
        $type = $event->subject->entity->type;
        if (empty($type)) {
            $passedArgs = $this->_request()->param('pass');
            $type = $passedArgs[0];
        }

        $event->subject->entity->type = $type;

        $data = [
            'user_id' => $this->_controller()->Auth->user('id'),
            'type' => $type,
        ] + $this->_request()->data() + ['published_date' => Time::now()];
        $postType = $event->subject->entity->getPostType();
        $data = $postType->execute($data);

        $PostsTable = TableRegistry::get('Posts');
        $PostsTable->patchEntity($event->subject->entity, $data);
    }

    /**
     * Before Handle Index Action
     *
     * @param \Cake\Event\Event $event Event
     * @return void
     */
    public function beforeHandleIndex(Event $event)
    {
        $this->_action()->config('scaffold.fields', [
            'id',
            'title',
            'status' => [
                'formatter' => function ($name, $value, $entity) {
                    $type = $value == 'active' ? 'success' : 'default';
                    return sprintf('<span class="label label-%s">%s</span>', $type, $value);
                },
            ],
            'published_date',
        ]);
        $this->_controller()->set('indexActions', $this->_getIndexActions());
        $this->_action()->config('scaffold.viewblocks', [
            'actions' => [
                'admin/Posts/index-actions' => 'element',
            ],
        ]);
    }

    /**
     * Get valid actions for the index page
     *
     * @return array
     */
    protected function _getIndexActions()
    {
        $indexActions = [];
        $postTypes = PostsTable::postTypes();
        foreach ($postTypes as $class => $alias) {
            $indexActions[] = [
                'title' => __('Add {0}', $alias),
                'url' => ['controller' => 'Posts', 'action' => 'add', $alias],
                'options' => ['class' => 'btn btn-default'],
                'method' => 'GET',
            ];
        }
        return $indexActions;
    }

    /**
     * Before Handle Home Action
     *
     * @param \Cake\Event\Event $event Event
     * @return void
     */
    public function beforeHandleHome(Event $event)
    {
        $this->_action()->config('findMethod', 'blog');
    }

    /**
     * Before Render Add Action
     *
     * @param \Cake\Event\Event $event Event
     * @return void
     */
    public function beforeRenderAdd(Event $event)
    {
        $passedArgs = $this->_request()->param('pass');
        if (!PostsTable::isValidPostType($passedArgs)) {
            return $this->_controller()->redirect([
                'controller' => 'Posts',
                'action' => 'index',
            ]);
        }

        $event->subject->entity->type = $passedArgs[0];
        $this->_setPostType($event, $event->subject->entity->getPostType());
    }

    /**
     * Before Render Edit Action
     *
     * @param \Cake\Event\Event $event Event
     * @return void
     */
    public function beforeRenderEdit(Event $event)
    {
        $entity = $event->subject->entity;
        $this->_setPostType($event, $entity->getPostType());
        if ($this->_request()->is('get')) {
            $this->_request()->data = $event->subject->entity->data($entity);
        }
    }

    /**
     * Set the post type for add/edit actions
     *
     * @param \Cake\Event\Event $event Event
     * @param string $postType the name of a post type class
     * @return void
     */
    protected function _setPostType(Event $event, AbstractPostType $postType)
    {
        $fields = [];
        foreach ($postType->schema()->fields() as $field) {
            $fields[$field] = [
                'type' => $postType->schema()->fieldType($field)
            ];
        }

        $viewVars = $postType->viewVars();
        $viewVars['fields'] = $fields;
        $this->_controller()->set($viewVars);
        $event->subject->set(['entity' => $postType]);
    }
}
