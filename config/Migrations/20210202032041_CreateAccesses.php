<?php
use Migrations\AbstractMigration;

class CreateAccesses extends AbstractMigration
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
        $table = $this->table('accesses');
        $table->addColumn('yyyy', 'integer', [
            'default' => null,
            'limit' => 11,
            'null' => false,
        ]);
        $table->addColumn('mm', 'integer', [
            'default' => null,
            'limit' => 11,
            'null' => false,
        ]);
        $table->addColumn('dd', 'integer', [
            'default' => null,
            'limit' => 11,
            'null' => false,
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
            'yyyy',
        ], [
            'name' => 'BY_YYYY',
            'unique' => false,
        ]);
        $table->addIndex([
            'mm',
        ], [
            'name' => 'BY_MM',
            'unique' => false,
        ]);
        $table->addIndex([
            'dd',
        ], [
            'name' => 'BY_DD',
            'unique' => false,
        ]);
        $table->create();
    }
}
