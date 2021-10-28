<?php

use Illuminate\Database\Seeder;
use TCG\Voyager\Traits\Seedable;

class TDatabaseSeeder extends Seeder
{
    use Seedable;

    protected $seedersPath = __DIR__.'/';

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->seed('TDataTypesSeeder');
        $this->seed('TPositionsTableSeeder');

    }
}
