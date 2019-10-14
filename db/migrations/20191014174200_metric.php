<?php

use Phinx\Migration\AbstractMigration;

class Metric extends AbstractMigration
{
    public function up()
    {
        $this->table('metric')
            ->addColumn('name', 'string', ['limit' => 100])
            ->save();

        $this->table('metric')
            ->insert(['name' => 'tma'])
            ->insert(['name' => 'tme'])
            ->insert(['name' => 'tmo'])
            ->saveData();
    }

    public function down()
    {
        $this->table('metric')->drop()->save();
    }
}
