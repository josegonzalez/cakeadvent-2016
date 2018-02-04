<?php
use Cake\Core\Configure;
use Cake\Event\Event;
use Cake\Event\EventManager;

EventManager::instance()->on('Posts.PostTypes.get', function (Event $event) {
  // The key is the Plugin name and the class
  // The value is what you want to display in the ui
  $event->getSubject()->postTypes['PhotoPostType.PhotoPostType'] = 'photo';
});

EventManager::instance()->on('View.initialize', function (Event $event) {
    $event->getSubject()->loadHelper('ADmad/Glide.Glide', [
        // Base URL.
        'baseUrl' => '/images/',
        // Whether to generate secure URLs.
        'secureUrls' => (bool)Configure::read('PhotoPostType.secureUrls', true),
        // Signing key to use when generating secure URLs.
        'signKey' => Configure::read('PhotoPostType.signKey', 1234),
    ]);
});
