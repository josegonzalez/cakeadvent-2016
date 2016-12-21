<?php
use Cake\Routing\Router;
$url = Router::url(
    [
        'plugin' => null,
        'controller' => 'users',
        'action' => 'resetPassword',
        $token
    ],
    true
);
?>
<html>
<head>
    <title><?= $this->fetch('title') ?></title>
</head>
<body>
    <?= $this->fetch('content') ?>
    <h1>Set your password...</h1>
    <p>
        A password recovery link has been requested for your account. If you
        haven't requested this, please ignore this email.
    </p>
    <p>
        <?= $this->Html->link('Click here to reset your password', $url) ?>
    </p>
</body>
</html>
