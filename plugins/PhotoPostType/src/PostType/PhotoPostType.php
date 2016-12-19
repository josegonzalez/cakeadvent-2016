<?php
namespace PhotoPostType\PostType;

use App\PostType\AbstractPostType;
use Cake\Form\Schema;
use Cake\Validation\Validator;

class PhotoPostType extends AbstractPostType
{
    protected function _buildSchema(Schema $schema)
    {
        $schema = parent::_buildSchema($schema);
        $schema->addField('photo', ['type' => 'file']);
        $schema->addField('photo_dir', ['type' => 'hidden']);
        $schema->addField('photo_path', ['type' => 'hidden']);
        return $schema;
    }

    protected function _buildValidator(Validator $validator)
    {
        $validator = parent::_buildValidator($validator);
        $validator->add('photo', 'valid-image', [
            'rule' => ['uploadedFile', [
                'types' => [
                    'image/bmp',
                    'image/gif',
                    'image/jpeg',
                    'image/pjpeg',
                    'image/png',
                    'image/vnd.microsoft.icon',
                    'image/x-windows-bmp',
                    'image/x-icon',
                    'image/x-png',
                ],
                'optional' => true,
            ]],
            'message' => 'The uploaded photo was not a valid image'
        ]);
        return $validator;
    }
}
