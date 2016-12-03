<?php
use Cake\Routing\Router;
$url = Router::url(
    [
        'controller' => 'users',
        'action' => 'resetPassword',
        $token
    ],
    true
);
?>

A password recovery link has been requested for your account. If you haven't requested this, please ignore this email.

Click here to reset your password: <?= $url ?>
