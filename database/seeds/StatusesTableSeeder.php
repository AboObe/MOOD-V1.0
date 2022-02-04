<?php

use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StatusesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('statuses')->insert([
            'id' => 1,
            'name' => 'delete',
        ]);
        DB::table('statuses')->insert([
            'id' => 2,
            'name' => 'active',
        ]);
    }
}
