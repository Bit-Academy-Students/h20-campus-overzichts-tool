<?php

namespace Database\Seeders;

use App\Models\AssignmentClaim;
use App\Models\Assignments;
use App\Models\Contactpersoon;
use App\Models\Keywords;
use App\Models\Opdrachtgever;
use App\Models\Opdrachtnemer;
use App\Models\Sources;
use App\Models\Tags;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::factory()->create([
            'first_name' => 'Admin',
            'last_name' => 'User',
            'email' => 'admin@example.com',
            'role' => 'admin',
        ]);
        $test = User::factory()->create([
            'first_name' => 'Test',
            'last_name' => 'User',
            'email' => 'test@example.com',
        ]);
        $users = User::factory(4)->create()->push($admin, $test);

        $tags = collect(['ICT', 'Webdesign', 'Bouw', 'Energie', 'Zorg', 'Onderwijs', 'Mobiliteit', 'Groen', 'Data', 'Duurzaamheid'])
            ->map(fn (string $name) => Tags::create(['name' => $name]));

        collect(['aanbesteding', 'opdracht', 'gemeente', 'inschrijving', 'offerte'])
            ->each(fn (string $word) => Keywords::create(['word' => $word]));

        Sources::factory(3)->create();
        Sources::factory()->inactive()->create();

        $opdrachtnemers = Opdrachtnemer::factory(15)->create();
        $opdrachtnemers->each(fn (Opdrachtnemer $o) => $o->tags()->attach($tags->random(random_int(2, 4))->pluck('id')));

        $opdrachtgevers = Opdrachtgever::factory(8)->create();
        $opdrachtgevers->each(fn (Opdrachtgever $o) => Contactpersoon::factory(random_int(1, 3))->create(['opdrachtgever_id' => $o->id]));

        $assignments = Assignments::factory(30)->make()->each(function (Assignments $a) use ($opdrachtgevers) {
            $a->opdrachtgever_id = $opdrachtgevers->random()->id;
            $a->save();
        });
        $assignments->each(fn (Assignments $a) => $a->tags()->attach($tags->random(random_int(1, 3))->pluck('id')));

        // Een deel van de opdrachten is geclaimd, deels al aan een bedrijf toegewezen.
        $assignments->shuffle()->take(12)->each(function (Assignments $a, int $i) use ($users, $opdrachtnemers) {
            $claim = AssignmentClaim::factory()->state([
                'assignment_id' => $a->id,
                'user_id' => $users->random()->id,
            ]);

            match (true) {
                $i < 5 => $claim->create(),
                $i < 9 => $claim->state(['opdrachtnemer_id' => $opdrachtnemers->random()->id, 'status' => 'assigned', 'assigned_at' => now()])->create(),
                default => $claim->state(['opdrachtnemer_id' => $opdrachtnemers->random()->id, 'status' => 'completed', 'assigned_at' => now()->subDays(3)])->create(),
            };
        });

        // Favorieten per user.
        $users->each(fn (User $u) => $u->favorites()->attach($assignments->random(random_int(1, 5))->pluck('id')));
    }
}
