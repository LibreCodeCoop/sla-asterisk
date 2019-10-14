<?php

use Phinx\Migration\AbstractMigration;

class Config extends AbstractMigration
{
    public function change()
    {
        $this->table('config')
            ->addColumn('queue', 'string', ['limit' => 100])
            ->addColumn('sla', 'integer')
            ->addColumn('window', 'integer')
            ->addColumn('metric_id', 'integer')
            ->addForeignKey('metric_id', 'metric', 'id', ['delete'=> 'CASCADE', 'update'=> 'CASCADE'])
            ->save();
    }
}
