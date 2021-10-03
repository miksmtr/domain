<?php

namespace Database\Seeders;

use App\Models\ServerSetting;
use Illuminate\Database\Seeder;

class ServerSettingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $ss =  [
            ['is_server_busy' => 0],
          ];

          ServerSetting::insert($ss);
    }
}
