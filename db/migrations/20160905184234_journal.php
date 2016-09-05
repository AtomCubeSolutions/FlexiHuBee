<?php

use Phinx\Migration\AbstractMigration;

class Journal extends AbstractMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-abstractmigration-class
     *
     * The following commands can be used in this method and Phinx will
     * automatically reverse them when rolling back:
     *
     *    createTable
     *    renameTable
     *    addColumn
     *    renameColumn
     *    addIndex
     *    addForeignKey
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change()
    {
        $table = $this->table('journal');
        $table
            ->addColumn('instance', 'integer',
                ['comment' => 'FlexiBee instance number'])
            ->addColumn('evidence', 'string',
                ['comment' => 'RestAPI endpoint url'])
            ->addColumn('topid', 'integer',
                ['comment' => 'Latest synced record ID'])
            ->addColumn('DatCreate', 'datetime')
            ->addColumn('DatSave', 'datetime', ['null' => 'true'])
            ->addIndex(['evidence', 'instance'],
                ['unique' => true, 'name' => 'uniq'])
            ->create();
    }
}
