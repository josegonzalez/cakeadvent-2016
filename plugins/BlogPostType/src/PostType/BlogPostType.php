<?php
namespace BlogPostType\PostType;

use App\PostType\AbstractPostType;
use Cake\Form\Schema;
use Cake\Validation\Validator;

class BlogPostType extends AbstractPostType
{
    protected function _buildSchema(Schema $schema)
    {
        $schema = parent::_buildSchema($schema);
        $this->_addField($schema, 'body', 'textarea');

        return $schema;
    }

    public function validationBody(Validator $validator)
    {
        $validator->notEmpty('body', 'Please fill this field');

        return $validator;
    }
}
