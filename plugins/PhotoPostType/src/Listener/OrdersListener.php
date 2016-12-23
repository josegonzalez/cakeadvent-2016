<?php
namespace PhotoPostType\Listener;

use Cake\Event\Event;
use Crud\Listener\BaseListener;

/**
 * Orders Listener
 */
class OrdersListener extends BaseListener
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
        if ($event->subject->action === 'index') {
            $this->beforeHandleIndex($event);

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
            'chargeid' => [
                'formatter' => 'element',
                'element' => 'PhotoPostType.crud-view/index-chargeid',
            ],
            'contact' => [
                'formatter' => 'element',
                'element' => 'PhotoPostType.crud-view/index-contact',
            ],
            'shipped' => [
            ],
            'created' => [
            ],
        ]);
    }
}
