<?php

namespace Database\Seeders;

use App\Models\Assignment_Claims;
use App\Models\Assignments;
use App\Models\Companies;
use App\Models\Keywords;
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

        $companies = Companies::factory(15)->create();
        $companies->each(fn (Companies $c) => $c->tags()->attach($tags->random(random_int(2, 4))->pluck('id')));

        $assignments = Assignments::factory(30)->create();
        $assignments->each(fn (Assignments $a) => $a->tags()->attach($tags->random(random_int(1, 3))->pluck('id')));

        // Een deel van de opdrachten is geclaimd, deels al aan een bedrijf toegewezen.
        $assignments->shuffle()->take(12)->each(function (Assignments $a, int $i) use ($users, $companies) {
            $claim = Assignment_Claims::factory()->state([
                'assignment_id' => $a->id,
                'user_id' => $users->random()->id,
            ]);

            match (true) {
                $i < 5 => $claim->create(),
                $i < 9 => $claim->state(['company_id' => $companies->random()->id, 'status' => 'assigned', 'assigned_at' => now()])->create(),
                default => $claim->state(['company_id' => $companies->random()->id, 'status' => 'completed', 'assigned_at' => now()->subDays(3)])->create(),
            };
        });

        // Favorieten per user.
        $users->each(fn (User $u) => $u->favorites()->attach($assignments->random(random_int(1, 5))->pluck('id')));
    }
}
