<?php
namespace PhotoPostType\Listener;

use Cake\Event\Event;
use Cake\Routing\Router;
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

        if ($event->subject->action === 'setShipped') {
            $this->beforeHandleSetShipped($event);

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
        $this->_action()->config('scaffold.bulk_actions', [
            Router::url(['action' => 'setShipped', 'shipped' => '1']) => __('Mark as shipped'),
            Router::url(['action' => 'setShipped', 'shipped' => '0']) => __('Mark as unshipped'),
        ]);
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

    /**
     * Before Handle SetShipped Action
     *
     * @param \Cake\Event\Event $event Event
     * @return void
     */
    public function beforeHandleSetShipped(Event $event)
    {
        $value = (int)$this->_request()->query('shipped');
        if ($value !== 0 && $value !== 1) {
            throw new BadRequestException('Invalid ship status specified');
        }

        $verb = 'shipped';
        if ($value === 0) {
            $verb = 'unshipped';
        }

        $this->_action()->config('value', $value);
        $this->_action()->config('messages.success.text', sprintf('Marked orders as %s!', $verb));
        $this->_action()->config('messages.error.text', sprintf('Could not mark orders as %s!', $verb));
    }
}
