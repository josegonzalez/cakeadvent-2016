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
        $postTypes = PostsListener::postTypes();
        $request = $this->_request();
        $passedArgs = $request->param('pass');

        $postType = null;
        if (!empty($passedArgs)) {
            $type = $passedArgs[0];
            foreach ($postTypes as $class => $alias) {
                if ($alias === $type) {
                    $postType = $class;
                }
            }
        }

        if ($postType !== null) {
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
}
