<?php
namespace PhotoPostType\PostType;

use App\PostType\AbstractPostType;
use Cake\Collection\Collection;
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
        $this->_addField($schema, 'series', 'text');
        $this->_addField($schema, 'photo', 'file');
        $this->_addField($schema, 'photo_dir', 'hidden');
        $this->_addField($schema, 'photo_path', 'hidden');
        $this->_addField($schema, 'price', 'text');
        $this->_addField($schema, 'description', 'textarea');
        return $schema;
    }

    /**
     * Photo validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationPhoto(Validator $validator)
    {
        $validator->add('value', 'valid-image', [
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

    /**
     * Price validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationPrice(Validator $validator)
    {
        $validator->allowEmpty('value', true);
        $validator->add('value', 'numeric', [
            'rule' => ['naturalNumber', true],
            'message' => 'This field must be a number',
        ]);

        return $validator;
    }

    /**
     * Description validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDescription(Validator $validator)
    {
        $validator->allowEmpty('value', true);

        return $validator;
    }

    public function getPriceInCents()
    {
        $price = $this->get('price');
        if (empty($price)) {
            return 0;
        }

        return $price * 100;
    }

    public function transformData($data)
    {
        $photoData = (new Collection($data['post_attributes']))->filter(function($data) {
            return $data['name'] === 'photo';
        })->first();

        $photoExtension = pathinfo($photoData['value']['name'], PATHINFO_EXTENSION);
        $photoDirectory  = 'files/Posts/photo/' . uniqid();
        $photoFilename = uniqid() . '.' . $photoExtension;
        $photoPath = $photoDirectory . '/' . $photoFilename;
        $postAttributes = [
            ['name' => 'photo_dir', 'value' => $photoDirectory],
            ['name' => 'photo', 'value' => $photoData['value']['name']],
            ['name' => 'photo_path', 'value' => $photoPath],
        ];

        $success = $this->writeFile($photoData['value'], $photoPath);
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
