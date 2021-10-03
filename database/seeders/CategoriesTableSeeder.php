<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data =  [
            ['name' => 'Kategorisiz'],
            ['name' => 'Bahis Haberleri'],
            ['name' => 'Deneme Bonusu'],
          ];

          Category::insert($data);
    }
}
