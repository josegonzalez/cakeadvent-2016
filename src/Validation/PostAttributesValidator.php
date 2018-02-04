<?php
namespace App\Validation;

use Cake\Core\App;
use Cake\Event\Event;
use Cake\Event\EventManager;
use Crud\Event\Subject;
use Cake\Utility\Hash;
use Cake\Utility\Inflector;
use Cake\Validation\Validator;

class PostAttributesValidator extends Validator
{
    protected $_validators = [];

    protected $_field = null;

    public function __construct()
    {
        parent::__construct();

        $subject = new Subject([
            'postTypes' => [],
        ]);
        $event = new Event('Posts.PostTypes.get', $subject);

        EventManager::instance()->dispatch($event);
        if (empty($event->getSubject()->postTypes)) {
            return;
        }

        $classes = array_keys($event->getSubject()->postTypes);
        foreach ($classes as $postTypeClassName) {
            $className = App::className($postTypeClassName, 'PostType');
            $postType = new $className();
            $classMethods = get_class_methods($className);
            foreach ($classMethods as $classMethod) {
                if (substr($classMethod, 0, 10) !== 'validation') {
                    continue;
                }

                $field = Inflector::underscore(substr($classMethod, 10));
                $validator = $postType->$classMethod(new Validator());
                $this->addFieldValidator($field, $validator);
            }
        }
    }

    public function addFieldValidator($value, $validator)
    {
        $this->_validators[$value] = $validator;
    }

    public function errors(array $data, $newRecord = true)
    {
        $fieldName = Hash::get($data, 'name', null);
        $validator = Hash::get($this->_validators, $fieldName, null);
        if ($validator === null) {
            return parent::errors($data, $newRecord);
        }

        return $validator->errors($data, $newRecord);
    }

    /**
     * Get the printable version of this object.
     *
     * @return array
     */
    public function __debugInfo()
    {
        $debugInfo = parent::__debugInfo();
        $debugInfo['_validators'] = $this->_validators;
        return $debugInfo;
    }
}
