<?php
namespace App\Form;

use Cake\Form\Schema;

/**
 * Contains the schema information for Form instances.
 */
class PostTypeSchema extends Schema
{
    /**
     * Adds a new field to the schema
     *
     * @return void
     */
    public function __construct()
    {
        $this->_fieldDefaults['label'] = null;
    }
}
