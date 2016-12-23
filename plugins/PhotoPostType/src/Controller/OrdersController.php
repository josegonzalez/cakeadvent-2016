<?php
namespace PhotoPostType\Controller;

use Cake\Core\Configure;
use PhotoPostType\Controller\AppController;
use Stripe\Error\Card as CardError;
use Stripe\Charge;
use Stripe\Customer;
use Stripe\Stripe;

class OrdersController extends AppController
{
    /**
     * A list of actions where the CrudView.View
     * listener should be enabled. If an action is
     * in this list but `isAdmin` is false, the
     * action will still be rendered via CrudView.View
     *
     * @var array
     */
    protected $adminActions = ['index', 'delete'];

    /**
     * Initialization hook method.
     *
     * Use this method to add common initialization code like loading components.
     *
     * e.g. `$this->loadComponent('Security');`
     *
     * @return void
     */
    public function initialize()
    {
        parent::initialize();
        $this->Crud->addListener('Orders', 'PhotoPostType\Listener\OrdersListener');
        $this->Crud->config('actions.add', null);
        $this->Crud->config('actions.edit', null);
        $this->Crud->config('actions.view', null);
        $this->Auth->allow('order');
    }

    /**
     * Check if the provided user is authorized for the request.
     *
     * @param array|\ArrayAccess|null $user The user to check the authorization of.
     *   If empty the user fetched from storage will be used.
     * @return bool True if $user is authorized, otherwise false
     */
    public function isAuthorized($user = null)
    {
        $action = $this->request->param('action');
        if (in_array($action, $this->adminActions)) {
            return true;
        }
        return parent::isAuthorized($user);
    }

    /**
     * Order action
     *
     * @return void
     */
    public function order()
    {
        $this->loadModel('Posts');
        $post = $this->Posts->find()
                           ->where(['id' => $this->request->query('id')])
                           ->contain('PostAttributes')
                           ->first()
                           ->getPostType();

        $charge = $this->chargeCard($post->getPriceInCents());
        if (empty($charge)) {
            $this->Flash->error(__('Your card was declined'));
            return $this->redirect($this->referer('/', true));
        }

        $this->createOrder($charge);
        $this->Flash->success(__('Order placed! Check your email for more details :)'));
        return $this->redirect($this->referer('/', true));
    }

    /**
     * Order action
     *
     * @return null|\Stripe\Charge
     */
    protected function chargeCard($amount)
    {
        Stripe::setApiKey(Configure::read('Stripe.secretkey'));
        try {
            $customer = Customer::create(array(
                'email' => $this->request->data('stripeEmail'),
                'card'  => $this->request->data('stripeToken')
            ));
            return Charge::create(array(
                'customer' => $customer->id,
                'amount'   => $amount,
                'currency' => 'usd'
            ));
        } catch (CardError $e) {
            $this->log($e);
            return null;
        }
    }

    /**
     * Order action
     *
     * @return null|\Stripe\Charge
     */
    protected function createOrder($charge)
    {
        $data = [
            'chargeid' => $charge->id,
            'email' => $this->request->data('stripeEmail'),
            'shipping_name' => $this->request->data('stripeShippingName'),
            'shipping_address_line_1' => $this->request->data('stripeShippingAddressLine1'),
            'shipping_address_zip' => $this->request->data('stripeShippingAddressZip'),
            'shipping_address_state' => $this->request->data('stripeShippingAddressState'),
            'shipping_address_city' => $this->request->data('stripeShippingAddressCity'),
            'shipping_address_country' => $this->request->data('stripeShippingAddressCountry'),
            'shipped' => false,
        ];

        $order = $this->Orders->newEntity($data);
        if (!$this->Orders->save($order)) {
            $this->log($order->errors());
        }
    }
}
