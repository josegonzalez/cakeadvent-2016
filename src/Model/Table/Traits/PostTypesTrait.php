<?php
namespace App\Model\Table\Traits;

use Cake\Event\Event;
use Cake\Event\EventManager;
use Crud\Event\Subject;

trait PostTypesTrait
{
    static $postTypes = null;

    public static function postTypes()
    {
        if (static::$postTypes !== null) {
            return static::$postTypes;
        }

        $event = new Event('Posts.PostTypes.get');
        $event->subject = new Subject([
            'postTypes' => [],
        ]);

        EventManager::instance()->dispatch($event);
        if (!empty($event->subject->postTypes)) {
            static::$postTypes = $event->subject->postTypes;
        } else {
            static::$postTypes = [];
        }
        return static::$postTypes;
    }

    /**
     * Checks if the passed arguments contain a valid post type
     *
     * @param string $passedArgs a list of passed request parameters
     * @return bool
     */
    public static function isValidPostType($passedArgs)
    {
        if (empty($passedArgs[0])) {
            return false;
        }

        $validPostType = false;
        $postTypes = static::postTypes();
        foreach ($postTypes as $class => $alias) {
            if ($passedArgs[0] === $alias) {
                $validPostType = true;
                break;
            }
        }
        return $validPostType;
    }
}
