<?php

use Phinx\Migration\AbstractMigration;

class MetricOrder extends AbstractMigration
{
    public function up()
    {
        $this->table('metric')
            ->addColumn('order', 'integer')
            ->save();

        $this->execute('UPDATE metric SET `order` = id');
    }
    public function down()
    {
        $this->table('metric')
            ->removeColumn('order')
            ->save();
    }
}
