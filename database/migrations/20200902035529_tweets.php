<?php
use think\migration\Migrator;
use think\migration\db\Column;
class Tweets extends Migrator
{
    /**
     * Migrate Up.
     */
    public function up()
    {
        // create the table
        $table = $this->table('tweets');
        $table->addColumn('content', 'text')
            ->addColumn('user_id', 'integer')
            // 统计点赞总数
            ->addColumn('likes', 'integer')
            // 统计转发总数
            ->addColumn('retweets', 'integer')
            ->addTimestamps('created_at', 'updated_at')
            ->create();
    }
    /**
     * Migrate Down.
     */
    public function down()
    {
        $this->dropTable('tweets');
    }
}