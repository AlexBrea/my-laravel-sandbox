<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Seeders\CreateAdminUser;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            CreateAdminUser::class,
        ]);
    }
}
