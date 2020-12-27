<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use \App\Models\User;

/**
 * Class DatabaseSeeder
 * @package Database\Seeders
 */
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
         User::factory(10)
             ->hasPosts(10)
             ->create();

         //for testing
         User::find(1)
             ->allMutedUsers()
             ->sync([
                2,3,4
            ]);
    }
}
