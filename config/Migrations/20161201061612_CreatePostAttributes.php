<?php
use Migrations\AbstractMigration;

class CreatePostAttributes extends AbstractMigration
{
    /**
     * Change Method.
     *
     * More information on this method is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-change-method
     * @return void
     */
    public function change()
    {
        $table = $this->table('post_attributes');
        $table->addColumn('post_id', 'integer', [
            'default' => null,
            'limit' => 11,
            'null' => false,
        ]);
        $table->addColumn('name', 'string', [
            'default' => null,
            'limit' => 100,
            'null' => false,
        ]);
        $table->addColumn('value', 'text', [
            'default' => null,
            'null' => false,
        ]);
        $table->addIndex([
            'post_id',
        ], [
            'name' => 'BY_POST_ID',
            'unique' => false,
        ]);
        $table->create();
    }
}
