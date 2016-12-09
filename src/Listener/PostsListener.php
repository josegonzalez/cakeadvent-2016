<?php
namespace App\Listener;

use Cake\Core\App;
use Cake\Event\Event;
use Crud\Listener\BaseListener;

/**
 * Posts Listener
 */
class PostsListener extends BaseListener
{
    use \App\Traits\PostTypesTrait;

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
        if ($this->_controller()->request->action === 'index') {
            $this->beforeHandleIndex($event);

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
        if ($this->_controller()->request->action === 'add') {
            $this->beforeRenderAdd($event);

            return;
        }

        if ($this->_controller()->request->action === 'edit') {
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
        $event->subject->entity->user_id = $this->_controller()->Auth->user('id');
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
        $className = null;
        if (!empty($passedArgs)) {
            $className = $this->_postTypeAliasToClass($passedArgs[0]);
        }

        if ($className !== null) {
            $this->_setPostType($event, $className);
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
        $entity = $event->subject->entity;
        $className = $this->_postTypeAliasToClass($entity->type);
        $this->_setPostType($event, $className);
        if ($this->_request()->is('get')) {
            $this->_request()->data = $event->subject->entity->data($entity);
        }
    }

    /**
     * Returns a class name for a given post type alias
     *
     * @param string $typeAlias the alias of a post type class
     * @return string
     */
    public function _postTypeAliasToClass($typeAlias)
    {
        $className = null;
        $postTypes = PostsListener::postTypes();
        foreach ($postTypes as $class => $alias) {
            if ($alias === $typeAlias) {
                $className = $class;
            }
        }
        return $className;
    }

    /**
     * Set the post type for add/edit actions
     *
     * @param \Cake\Event\Event $event Event
     * @param string $postType the name of a post type class
     * @return void
     */
    protected function _setPostType(Event $event, $postType)
    {
        $className = App::className($postType, 'PostType');
        $postType = new $className;
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
