<h2>Your order was recieved</h2>
<p>
    Thanks for your order! We will be shortly shipping out your order to the following address:
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
<p>Thanks again, and enjoy!</p>
