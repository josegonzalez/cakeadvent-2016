<?php
use Cake\Core\Configure;

$mode = Configure::read('Stripe.mode');
if ($mode === 'test') {
    echo $this->Html->link($value, sprintf('https://dashboard.stripe.com/test/payments/'. $value));
} else {
    echo $this->Html->link($value, sprintf('https://dashboard.stripe.com/payments/'. $value));
}
