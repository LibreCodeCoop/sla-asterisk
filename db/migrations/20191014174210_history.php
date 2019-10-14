<?php

use Phinx\Migration\AbstractMigration;

class History extends AbstractMigration
{
    public function change()
    {
        $this->table('history')
            ->addColumn('queue', 'string', ['limit' => 100])
            ->addColumn('sla', 'integer')
            ->addColumn('metric_id', 'integer')
            ->addColumn('created', 'timestamp', ['default' => 'CURRENT_TIMESTAMP'])
            ->addForeignKey('metric_id', 'metric', 'id', ['delete'=> 'CASCADE', 'update'=> 'CASCADE'])
            ->save();
    }
}
