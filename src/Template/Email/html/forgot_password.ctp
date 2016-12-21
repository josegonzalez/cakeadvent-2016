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
    <h2>Resetting your password?</h2>
    <p>
        A password recovery link has been requested for your account. If you
        haven't requested this, please ignore this email.
    </p>
    <p>
        <?= $this->Html->link('Click here to reset your password', $url, [
            'target' => '_blank',
            'style' => "text-decoration:underline;background-color:#ffffff;border:solid 1px #3498db;border-radius:5px;box-sizing:border-box;color:#3498db;cursor:pointer;display:inline-block;font-size:14px;font-weight:bold;margin:0;padding:12px 25px;text-decoration:none;text-transform:capitalize;background-color:#3498db;border-color:#3498db;color:#ffffff;",
        ]) ?>
    </p>
</body>
</html>
