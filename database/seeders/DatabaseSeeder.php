<?php

namespace Database\Seeders;

use App\Models\Team;
use App\Models\User;
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
        $this->call([PermissionSeeder::class]);

        User::factory(['email' => 'info@geisi.dev'])
            ->withPersonalTeam()
            ->hasAttached(Team::factory()->count(3))
            ->create();
    }
}
