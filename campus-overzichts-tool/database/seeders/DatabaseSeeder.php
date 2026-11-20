<?php

namespace Database\Seeders;

use App\Models\AssignmentClaim;
use App\Models\Assignments;
use App\Models\Client;
use App\Models\ContactPerson;
use App\Models\Contractor;
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

        $contractors = Contractor::factory(15)->create();
        $contractors->each(function (Contractor $c) use ($tags) {
            $c->tags()->attach($tags->random(random_int(2, 4))->pluck('id'));
            ContactPerson::factory(random_int(1, 2))->forContractor($c)->create();
        });

        $clients = Client::factory(8)->create();
        $clients->each(function (Client $c) use ($tags) {
            $c->tags()->attach($tags->random(random_int(1, 3))->pluck('id'));
            ContactPerson::factory(random_int(1, 3))->create(['client_id' => $c->id]);
        });

        // Elke opdracht hoort bij een client; de contactpersoon is een van de contactpersonen van die client.
        $assignments = Assignments::factory(30)->make()->each(function (Assignments $a) use ($clients) {
            $client = $clients->random();
            $a->client_id = $client->id;
            $a->contact_person_id = $client->contactPersons()->inRandomOrder()->value('id');
            $a->status = fake()->randomElement(['new', 'in_review', 'accepted', 'rejected']);
            $a->save();
        });
        $assignments->each(fn (Assignments $a) => $a->tags()->attach($tags->random(random_int(1, 3))->pluck('id')));

        // Een deel van de opdrachten is geclaimd, deels al aan een contractor toegewezen.
        // De status van de opdracht volgt de claim: geclaimd = in_review, toegewezen/afgerond = assigned.
        $assignments->shuffle()->take(12)->each(function (Assignments $a, int $i) use ($users, $contractors) {
            $claim = AssignmentClaim::factory()->state([
                'assignment_id' => $a->id,
                'user_id' => $users->random()->id,
            ]);

            match (true) {
                $i < 5 => $claim->create(),
                $i < 9 => $claim->state(['contractor_id' => $contractors->random()->id, 'status' => 'assigned', 'assigned_at' => now()])->create(),
                default => $claim->state(['contractor_id' => $contractors->random()->id, 'status' => 'completed', 'assigned_at' => now()->subDays(3)])->create(),
            };

            $a->update(['status' => $i < 5 ? 'in_review' : 'assigned']);
        });

        // Favorieten per user.
        $users->each(fn (User $u) => $u->favorites()->attach($assignments->random(random_int(1, 5))->pluck('id')));
    }
}
