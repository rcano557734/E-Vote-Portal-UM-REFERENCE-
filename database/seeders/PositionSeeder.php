<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Position;
use App\Models\Election;

class PositionSeeder extends Seeder
{
    public function run(): void
    {
        // Disable FK checks so truncate works safely
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('positions')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Reuse existing election OR create one with ALL required fields
        $election = Election::first();
        if (!$election) {
            $election = Election::create([
                'title'      => 'UM General Election ' . date('Y'),
                'start_date' => now()->toDateString(),
                'end_date'   => now()->addDays(7)->toDateString(),
                'status'     => 'pending',
            ]);
        }

        // Official UM CCSG Positions
        $positions = [
            'President',
            'Internal Vice President',
            'External Vice President',
            'Secretary',
            'Asst. Secretary',
            'Treasurer',
            'Asst. Treasurer',
            'Auditor',
            'P.I.O',
            'Asst. P.I.O',
            'Business Manager',
        ];

        foreach ($positions as $pos) {
            Position::create([
                'position_name' => $pos,
                'election_id'   => $election->id,
            ]);
        }
    }
}