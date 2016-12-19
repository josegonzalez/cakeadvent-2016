<!DOCTYPE html>
<html>
<head>
    <?= $this->Html->charset() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?= $this->fetch('title') ?>
    </title>
    <?= $this->Html->meta('icon') ?>

      <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
    <?= $this->Html->css('style.css') ?>

    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>
    <?= $this->fetch('script') ?>
</head>
<body>
    <header class="navigation" role="banner">
        <div class="navigation-wrapper">
            <a href="/" class="logo">
                <?= $this->Html->image('logo2.png')?>
            </a>
            <a href="javascript:void(0)" class="navigation-menu-button" id="js-mobile-menu">
                <i class="fa fa-bars"></i>
            </a>
            <nav role="navigation">
                <ul id="js-navigation-menu" class="navigation-menu show">
                    <li class="nav-link"><a href="/about">About</a>
                    <li class="nav-link"><a href="/">Posts</a>
                </ul>
            </nav>
        </div>
    </header>

    <div class="page-content">
        <?= $this->Flash->render() ?>
        <?= $this->fetch('content') ?>
    </div>
</body>
</html>
