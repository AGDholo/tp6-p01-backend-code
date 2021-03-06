<?php
use think\migration\Migrator;
use think\migration\db\Column;
class Followers extends Migrator
{
    /**
     * Migrate Up.
     */
    public function up()
    {
        // create the table
        $table = $this->table('followers');
        $table->addColumn('follower_id', 'integer')
            ->addColumn('user_id', 'integer')
            ->addTimestamps('created_at', 'updated_at')
            ->create();
    }
    /**
     * Migrate Down.
     */
    public function down()
    {
        $this->dropTable('followers');
    }
}