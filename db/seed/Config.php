<?php


use Phinx\Seed\AbstractSeed;

class Config extends AbstractSeed
{
    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeders is available here:
     * http://docs.phinx.org/en/latest/seeding.html
     */
    public function run()
    {
        $this->table('config')
            ->insert([
                'window' => 60*60*24,
                'refresh' => 10,
                'sla' => 60,
                'queue' => 610,
                'metric_id' => 1
            ])
            ->insert([
                'window' => 60*60*24,
                'refresh' => 10,
                'sla' => 60,
                'queue' => 610,
                'metric_id' => 2
            ])
            ->insert([
                'window' => 60*60*24,
                'refresh' => 10,
                'sla' => 60,
                'queue' => 610,
                'metric_id' => 3
            ])
            ->saveData();
    }
}
