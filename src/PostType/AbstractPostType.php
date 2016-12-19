<?php
namespace App\PostType;

use App\Model\Entity\Post;
use Cake\Core\Configure;
use Cake\Form\Form;
use Cake\Form\Schema;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validator;
use Cake\Utility\Hash;
use Cake\Utility\Inflector;

abstract class AbstractPostType extends Form
{
    protected $_post = null;

    public function __construct(Post $post = null)
    {
        if ($post !== null) {
            $this->_post = $post;
        }
    }

    protected function _buildSchema(Schema $schema)
    {
        $schema->addField('user_id', ['type' => 'hidden']);
        $schema->addField('title', ['type' => 'string']);
        $schema->addField('url', ['type' => 'string']);
        $schema->addField('status', ['type' => 'select']);
        return $schema;
    }

    protected function _buildValidator(Validator $validator)
    {
        $validator->notEmpty('user_id', 'Please fill this field');
        $validator->notEmpty('title', 'Please fill this field');
        $validator->add('status', 'inList', [
            'rule' => ['inList', ['active', 'inactive']],
            'message' => 'Status must be either active or inactive'
        ]);
        return $validator;
    }

    protected function _execute(array $data)
    {
        if (empty($data['post_attributes'])) {
            $data['post_attributes'] = [];
        }
        $data = $this->transformData($data);
        $data['url'] = $this->ensureUrl($data);

        $PostsTable = TableRegistry::get('Posts');
        $AttributesTable = TableRegistry::get('PostAttributes');
        $postAttributes = $data['post_attributes'];

        $postColumns = $PostsTable->schema()->columns();
        $validColumns = $this->schema()->fields();
        foreach ($data as $key => $value) {
            if (in_array($key, $postColumns)) {
                continue;
            }

            unset($data[$key]);
            if (!in_array($key, $validColumns)) {
                continue;
            }
            $postAttributes[] = [
                'name' => $key,
                'value' => $value,
            ];
        }

        $data['post_attributes'] = $postAttributes;

        return $data;
    }

    public function transformData($data)
    {
        return $data;
    }

    public function get($key, $default = null)
    {
        if (empty($this->_data)) {
            $this->_data = $this->data();
        }

        if (isset($this->_data[$key])) {
            return $this->_data[$key];
        }
        return $default;
    }

    public function indexTemplate()
    {
        return $this->templatePrefix() . '-index';
    }

    public function viewTemplate()
    {
        return $this->templatePrefix() . '-view';
    }

    protected function ensureUrl(array $data)
    {
        $url = trim(Hash::get($data, 'url', ''), '/');
        if (strlen($url) !== 0) {
            return $url;
        }

        return Hash::get($data, 'title', '');
    }

    protected function templatePrefix()
    {
        $template = $className = get_class($this);
        $firstPos = $lastPos = 0;
        $prefix = null;

        if ($lastPos = strrpos($className, '\\')) {
            $template = substr($className, $lastPos + 1);
        }
        if ($firstPos = strpos($className, '\\')) {
            $prefix = substr($className, 0, $firstPos);
            if ($prefix === Configure::read('App.namespace')) {
                $prefix = null;
            }
        }

        $template = preg_replace('/PostType$/', '', $template);
        $template = 'post_type/' . Inflector::underscore($template);
        if ($prefix !== null) {
            $template = sprintf('%s.%s', $prefix, $template);
        }
        return $template;
    }

    public function data(Post $post = null)
    {
        if ($post === null) {
            $post = $this->_post;
        }
        if ($post === null) {
            return [];
        }

        $data = $post->toArray();
        unset($data['post_attributes']);
        unset($data['user']);
        foreach ((array)$post->post_attributes as $postAttribute) {
                $data[$postAttribute->name] = $postAttribute->value;
        }
        return $data;
    }

    public function viewVars()
    {
        $statuses = ['active' => 'active', 'inactive' => 'inactive'];
        return compact('statuses');
    }
}
