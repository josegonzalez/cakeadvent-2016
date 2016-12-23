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
    }
}
