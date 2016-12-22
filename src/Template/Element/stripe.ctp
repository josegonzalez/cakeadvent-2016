<?php
if (empty($post->get('price'))) {
    return;
}
?>

<div style="text-align:center;">
    <?= $this->Form->create(null, ['class' => 'payment-form', 'url' => ['plugin' => 'PhotoPostType', 'controller' => 'Orders', 'action' => 'order', 'id' => $post->get('id')]]); ?>
        <script
            src="https://checkout.stripe.com/checkout.js" class="stripe-button"
            data-key="<?= \Cake\Core\Configure::read('Stripe.publishablekey') ?>"
            data-amount="<?= $post->getPriceInCents() ?>"
            data-name="<?= \Cake\Core\Configure::read('App.name') ?>"
            data-description="<?= $post->get('title') ?>"
            data-image="https://stripe.com/img/documentation/checkout/marketplace.png"
            data-locale="auto"
            data-zip-code="true"
            data-billing-address="true"
            data-shipping-address="true"
            data-label="Buy this photo">
          </script>
    <?= $this->Form->end(); ?>
</div>
