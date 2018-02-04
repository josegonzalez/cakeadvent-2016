<?php
use Cake\Event\Event;
use Cake\Event\EventManager;

EventManager::instance()->on('Posts.PostTypes.get', function (Event $event) {
  // The key is the Plugin name and the class
  // The value is what you want to display in the ui
  $event->getSubject()->postTypes['BlogPostType.BlogPostType'] = 'blog';
});
