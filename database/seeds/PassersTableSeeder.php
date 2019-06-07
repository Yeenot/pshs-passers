<?php

use Illuminate\Database\Seeder;
use Goutte\Client;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class PassersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $client = new Client();
        $crawler = $client->request('GET', 'http://www.pshs.edu.ph/nce2018/');

        $header = [
            'id',
            'surname',
            'firstname',
            'campus_eligibility',
            'school',
            'division'
        ];

        $list = array();
        $crawler->filter('.container_list > div')->each(function ($row) use ($header, &$list) {
            $columns = $row->filter('.border_list');

            if (count($columns) == count($header)-1) {
                $info = array();
                $columns->each(function ($column, $index) use ($header, &$info) {

                    if ($index > 0) {
                        if ($index == 1) {
                            $name = explode(',', $column->text());
                            $info[$header[$index]] = $name[0];
                            $info[$header[$index + 1]] = trim($name[1]);
                        } else {
                            $info[$header[$index + 1]] = $column->text();
                        }
                    } else {
                        $info[$header[$index]] = $column->text();
                    }

                });
                $list[] = $info;
            }

        });
        
        $table = 'Passers';
        if (Schema::hasTable($table)) { 
            DB::table($table)->delete();
            DB::table($table)->insert($list);
        }
    }
}
