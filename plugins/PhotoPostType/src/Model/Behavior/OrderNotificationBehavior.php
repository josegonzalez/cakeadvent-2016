<?php
namespace PhotoPostType\Model\Behavior;

use Cake\Datasource\EntityInterface;
use Cake\Event\Event;
use Cake\ORM\Behavior;
use Josegonzalez\CakeQueuesadilla\Traits\QueueTrait;

class OrderNotificationBehavior extends Behavior
{
    use QueueTrait;

    public function afterSave(Event $event, EntityInterface $entity)
    {
        if ($entity->isNew()) {
            $this->push(['\App\Job\MailerJob', 'execute'], [
                'action' => 'received',
                'mailer' => 'PhotoPostType.Orders',
                'data' => [
                    'order_id' => $entity->id,
                    'email' => $entity->email,
                    'name' => $entity->shipping_name,
                    'address_line_1' => $entity->shipping_address_line_1,
                    'address_zip' => $entity->shipping_address_zip,
                    'address_state' => $entity->shipping_address_state,
                    'address_city' => $entity->shipping_address_city,
                    'address_country' => $entity->shipping_address_country,
                ]
            ]);

            $this->push(['\App\Job\MailerJob', 'execute'], [
                'action' => 'newOrder',
                'mailer' => 'PhotoPostType.Orders',
                'data' => [
                    'order_id' => $entity->id
                ],
            ]);
        } elseif ($entity->shipped) {
            $this->push(['\App\Job\MailerJob', 'execute'], [
                'action' => 'shipped',
                'mailer' => 'PhotoPostType.Orders',
                'data' => [
                    'order_id' => $entity->id
                ],
            ]);
        }
    }
}
