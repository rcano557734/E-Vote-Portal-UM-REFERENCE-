<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // 1. Seed Roles
    \App\Models\Role::insert([
        ['id' => 1, 'role_name' => 'Admin'],
        ['id' => 2, 'role_name' => 'Auditor'],
        ['id' => 3, 'role_name' => 'Voter'],
    ]);

    // 2. Seed an Election and Positions
    $election = \App\Models\Election::create([
        'title' => '2026 General Elections',
        'start_date' => now(),
        'end_date' => now()->addDays(7),
        'status' => 'Active'
    ]);

    \App\Models\Position::insert([
        ['election_id' => $election->id, 'position_name' => 'President'],
        ['election_id' => $election->id, 'position_name' => 'Vice President'],
        ['election_id' => $election->id, 'position_name' => 'Senator'],
    ]);
    }
}
