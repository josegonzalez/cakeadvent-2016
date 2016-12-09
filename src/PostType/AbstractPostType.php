<?php
namespace App\PostType;

use App\Model\Entity\Post;
use Cake\Form\Form;
use Cake\Form\Schema;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validator;
use Cake\Utility\Inflector;

abstract class AbstractPostType extends Form
{
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
        $validator->notEmpty('url', 'Please fill this field');
        $validator->add('status', 'inList', [
            'rule' => ['inList', ['active', 'inactive']],
            'message' => 'Status must be either active or inactive'
        ]);
        return $validator;
    }

    protected function _execute(array $data)
    {
        $postsTable = TableRegistry::get('Posts');
        $attributesTable = TableRegistry::get('PostAttributes');
        $postAttributes = [];

        $postFields = $postsTable->schema()->columns();
        foreach ($data as $key => $value)
        {
            if (in_array($key, $postFields)) {
                continue;
            }
            $postAttributes[] = $attributesTable->newEntity([
                'name' => $key,
                'value' => $value,
            ]);
            unset($data[$key]);
        }

        $post = $postsTable->newEntity($data);
        $post->post_attributes = $postAttributes;
        return $postsTable->save($post);
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
        return $this->templatePrefix() . '-index.ctp';
    }

    public function viewTemplate()
    {
        return $this->templatePrefix() . '-view.ctp';
    }

    protected function templatePrefix()
    {
        $template = get_class($this);
        if ($pos = strrpos($template, '\\')) {
            return substr($template, $pos + 1);
        }

        $template = preg_replace('/PostType$/', '', $template);
        return 'post_type/' . Inflector::underscore($template);
    }

    public function data(Post $post)
    {
        $data = $post->toArray();
        unset($data['post_attributes']);
        unset($data['user']);
        foreach ($post->post_attributes as $postAttribute) {
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
