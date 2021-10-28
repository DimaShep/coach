<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TDataTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        DB::table('t_data_types')->truncate();
        DB::table('t_data_types')->insert([
            'name' => 'Должность',
            'name_plural' => 'Должности',
            'slug' => 'positions',
            'model' => 'Shep\Coach\Models\Position',
        ]);

        DB::table('t_data_types')->insert([
            'name' => 'Карта',
            'name_plural' => 'Карты',
            'slug' => 'maps',
            'model' => 'Shep\Coach\Models\Map',
        ]);

        DB::table('t_data_types')->insert([
            'name' => 'Задание',
            'name_plural' => 'Задания',
            'slug' => 'tasks',
            'model' => 'Shep\Coach\Models\Task',
        ]);

        DB::table('t_data_types')->insert([
            'name' => 'Пользователь',
            'name_plural' => 'Пользователи',
            'slug' => 'users',
            'model' => 'App\User',
        ]);

        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
}
