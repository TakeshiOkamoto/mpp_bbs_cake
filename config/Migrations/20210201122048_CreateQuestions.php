<?php
use Migrations\AbstractMigration;

class CreateQuestions extends AbstractMigration
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
        $table = $this->table('questions');
        $table->addColumn('lang_type_id', 'integer', [
            'default' => null,
            'limit' => 11,
            'null' => false,
        ]);
        
        $table->addColumn('title', 'string', [
            'default' => null,
            'limit' => 150,
            'null' => false,
        ]);
        $table->addColumn('resolved', 'boolean', [
            'default' => false,
            'null' => true,
        ]);
        $table->addColumn('pv', 'biginteger', [
            'default' => null,
            'limit' => 20,
            'null' => false,
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
            'lang_type_id',
        ], [
            'name' => 'BY_LANG_TYPE_ID',
            'unique' => false,
        ]);
        
        // テーブル定義上は
        // titleがユニークになっていないのは移行前システムに同名タイトルがあった為
        $table->addIndex([
            'title',
        ], [
            'name' => 'BY_TITLE',
            'unique' => false,
        ]);
        $table->create();
    }
}
