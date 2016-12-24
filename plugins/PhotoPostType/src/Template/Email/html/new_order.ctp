<h2>There was a new order</h2>

<p>
    See the new order <?= $this->Html->link('here', \Cake\Routing\Router::url([
        'plugin' => 'PhotoPostType',
        'controller' => 'Orders',
        'action' => 'index',
        $token
    ], true)); ?>
</p>

<p>
    Order details:
</p>
<dl>
    <dt>name</dd>
    <dd><?= $order->name ?></dd>

    <dt>address</dd>
    <dd><?= $order->address_line_1 ?></dd>

    <dt>zip</dd>
    <dd><?= $order->address_zip ?></dd>

    <dt>state</dd>
    <dd><?= $order->address_state ?></dd>

    <dt>city</dd>
    <dd><?= $order->address_city ?></dd>

    <dt>countrys</dd>
    <dd><?= $order->address_country ?></dd>
</dl>
