<?php
namespace PhotoPostType\PostType;

use App\PostType\AbstractPostType;
use Cake\Form\Schema;
use Cake\Validation\Validator;
use League\Flysystem\Adapter\Local;
use League\Flysystem\AdapterInterface;
use League\Flysystem\Filesystem;

class PhotoPostType extends AbstractPostType
{
    protected function _buildSchema(Schema $schema)
    {
        $schema = parent::_buildSchema($schema);
        $schema->addField('photo', ['type' => 'file']);
        $schema->addField('photo_dir', ['type' => 'hidden']);
        $schema->addField('photo_path', ['type' => 'hidden']);
        $schema->addField('price', ['type' => 'text']);
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
        $validator->allowEmpty('price');
        $validator->add('price', 'numeric', [
            'rule' => ['naturalNumber', true]
        ]);
        return $validator;
    }

    public function transformData($data)
    {
        $photoExtension = pathinfo($data['photo']['name'], PATHINFO_EXTENSION);
        $photoDirectory  = 'files/Posts/photo/' . uniqid();
        $photoFilename = uniqid() . '.' . $photoExtension;
        $photoPath = $photoDirectory . '/' . $photoFilename;
        $postAttributes = [
            ['name' => 'photo_dir', 'value' => $photoDirectory],
            ['name' => 'photo', 'value' => $data['photo']['name']],
            ['name' => 'photo_path', 'value' => $photoPath],
        ];

        $success = $this->writeFile($data['photo'], $photoPath);
        unset($data['photo'], $data['photo_dir'], $data['photo_path'], $data['post_attributes']);
        if (!$success) {
            return $data;
        }

        $data['post_attributes'] = $postAttributes;

        return $data;
    }

    protected function writeFile(array $filedata, $filepath)
    {
        $success = false;
        $stream = @fopen($filedata['tmp_name'], 'r');
        if ($stream === false) {
            return $success;
        }

        $filesystem = $this->filesystem();
        $success = $filesystem->writeStream($filepath, $stream);
        fclose($stream);

        return $success;
    }

    protected function filesystem()
    {
        $adapter = new Local(WWW_ROOT);
        $filesystem = new Filesystem($adapter, [
            'visibility' => AdapterInterface::VISIBILITY_PUBLIC
        ]);

        return $filesystem;
    }
}
