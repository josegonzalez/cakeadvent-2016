<?php
namespace PhotoPostType\Mailer;

use Cake\Core\Configure;
use Cake\Mailer\Mailer;
use Josegonzalez\MailPreview\Mailer\PreviewTrait;

class OrderMailer extends Mailer
{

    use PreviewTrait;

    /**
     * Email sent on order received
     *
     * @param array $email User email
     * @param string $token Token used for validation
     * @return \Cake\Mailer\Mailer
     */
    public function received($data)
    {
        $this->loadModel('PhotoPostType.Orders');
        $order = $this->Orders->get($data['order_id']);
        return $this->to($order->email)
            ->subject('Order Received!')
            ->template('PhotoPostType.received')
            ->set($order)
            ->emailFormat('html');
    }

    /**
     * Email sent on new order
     *
     * @param array $email User email
     * @param string $token Token used for validation
     * @return \Cake\Mailer\Mailer
     */
    public function newOrder($data)
    {
        $this->loadModel('PhotoPostType.Orders');
        $order = $this->Orders->get($data['order_id']);
        return $this->to(Configure::read('Primary.email'))
            ->subject('New Order')
            ->template('PhotoPostType.new_order')
            ->set($order)
            ->emailFormat('html');
    }

    /**
     * Email sent on order shipped
     *
     * @param array $email User email
     * @param string $token Token used for validation
     * @return \Cake\Mailer\Mailer
     */
    public function shipped($data)
    {
        $this->loadModel('PhotoPostType.Orders');
        $order = $this->Orders->get($data['order_id']);
        return $this->to($order->email)
            ->subject('Order Shipped!')
            ->template('PhotoPostType.shipped')
            ->set($order)
            ->emailFormat('html');
    }
}
