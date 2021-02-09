<?php
use Migrations\AbstractMigration;

class CreateAnswers extends AbstractMigration
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
        $table = $this->table('answers');
        $table->addColumn('question_id', 'integer', [
            'default' => null,
            'limit' => 11,
            'null' => false,
        ]);
        $table->addColumn('name', 'string', [
            'default' => null,
            'limit' => 50,
            'null' => false,
        ]);
        $table->addColumn('url', 'string', [
            'default' => null,
            'limit' => 255,
            'null' => true,
        ]);
        $table->addColumn('body', 'text', [
            'default' => null,
            'null' => false,
        ]);
        $table->addColumn('ip', 'string', [
            'default' => null,
            'limit' => 255,
            'null' => true,
        ]);
        $table->addColumn('created_at', 'datetime', [
            'default' => null,
            'null' => false,
        ]);
        $table->addColumn('updated_at', 'datetime', [
            'default' => null,
            'null' => false,
        ]);
        $table->addIndex([
            'question_id',
        ], [
            'name' => 'BY_QUESTION_ID',
            'unique' => false,
        ]);
        $table->addIndex([
            'name',
        ], [
            'name' => 'BY_NAME',
            'unique' => false,
        ]);
        $table->addIndex([
            'ip',
        ], [
            'name' => 'BY_IP',
            'unique' => false,
        ]);
        $table->create();
    }
}
