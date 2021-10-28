<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TPositionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        DB::table('t_positions')->truncate();
        DB::table('t_positions')->insert([
            'id' => 1,
            'name' => 'Водитель',
        ]);
        DB::table('t_positions')->insert([
            'id' => 2,
            'name' => 'Директор',
        ]);

        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
}
