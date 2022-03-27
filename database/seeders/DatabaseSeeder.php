<?php

namespace Database\Seeders;

use App\Models\Song;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Song::create([
           "name"=>"tets",
           "albumId"=>1,
           "fileLink"=>"test"
        ]);
    }
}
