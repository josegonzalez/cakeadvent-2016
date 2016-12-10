<?php
namespace App\Listener;

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
        $type = $event->subject->entity->type;
        if (empty($type)) {
            $passedArgs = $this->_request()->param('pass');
            $type = $passedArgs[0];
        }

        $event->subject->entity->type = $type;
        $postTypeClassName = $this->_postTypeAliasToClass($type);
        $className = App::className($postTypeClassName, 'PostType');
        $postType = new $className;
        $validFields = $postType->schema()->fields();

        $postAttributes = [];
        $PostsTable = TableRegistry::get('Posts');
        $postColumns = $PostsTable->schema()->columns();
        foreach ($event->subject->entity->toArray() as $field => $value) {
            if (!in_array($field, $postColumns) && in_array($field, $validFields)) {
                $postAttributes[] = [
                    'name' => $field,
                    'value' => $value,
                ];
            }
        }

        $data = [
            'user_id' => $this->_controller()->Auth->user('id'),
            'type' => $type,
            'post_attributes' => $postAttributes,
        ] + $this->_request()->data;
        if (empty($data['published_date'])) {
            $data['published_date'] = Time::now();
        }

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
