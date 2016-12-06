<?php
use Migrations\AbstractMigration;

class AddAdminFieldToPosts extends AbstractMigration
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
        $table = $this->table('posts');
        $table->addColumn('title', 'string', [
            'default' => null,
            'limit' => 255,
            'null' => false,
        ]);
        $table->addColumn('published_date', 'datetime', [
            'default' => null,
            'null' => false,
        ]);
        $table->update();
    }
}
