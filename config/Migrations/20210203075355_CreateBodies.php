<?php
use Migrations\AbstractMigration;

// 追加分
use Phinx\Db\Adapter\MysqlAdapter;

class CreateBodies extends AbstractMigration
{
    /**
     * Change Method.
     *
     * More information on this method is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-change-method
     * @return void
     */
    public function change()
    {
        $table = $this->table('bodies');
        $table->addColumn('question_id', 'integer', [
            'default' => null,
            'limit' => 11,
            'null' => false,
        ]);
        $table->addColumn('matome', 'text', [
            'default' => null,
            'limit' => MysqlAdapter::TEXT_MEDIUM,
            'null' => false,
        ]);
        $table->addIndex([
            'question_id',
        ], [
            'name' => 'BY_QUESTION_ID',
            'unique' => false,
        ]);
        $table->create();
    }
}
