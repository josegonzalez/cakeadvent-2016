<?php
namespace App\PostType;

use Cake\ORM\TableRegistry;
use Cake\Utility\Inflector;

abstract class AbstractPostType extends Form
{
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
        return $this->templatePrefix() . '-index.ctp'
    }

    public function viewTemplate()
    {
        return $this->templatePrefix() . '-view.ctp'
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

    protected function _execute(array $data)
    {
        $postsTable = TableRegistry::get('Posts');
        $attributesTable = TableRegistry::get('PostAttributes');
        $postAttributes = [];

        $postFields = ['id', 'user_id', 'title', 'url'];
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

    public function data(Post $post)
    {
        $data = $post->toArray();
        unset($data['post_attributes']);
        foreach ($post->post_attributes as $postAttribute) {
                $data[$postAttribute->name] = $postAttribute->value;
        }
        return $data;
    }
}
