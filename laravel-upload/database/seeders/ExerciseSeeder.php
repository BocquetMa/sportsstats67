<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;
use App\Models\Exercise;

class ExerciseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Connexion à ExerciseDB (v1 Open Source)...');

        // On tente de récupérer les exercices
        // L'URL correcte est https://exercisedb.dev/api/v1/exercises
        $response = Http::withHeaders([
            'Accept' => 'application/json',
        ])->get('https://exercisedb.dev/api/v1/exercises', [
            'limit' => 100 // On commence par 100 pour tester la stabilité
        ]);

        if ($response->successful()) {
            $result = $response->json();

            // Dans la v1, les données sont dans la clé 'data'
            $exercises = $result['data'] ?? [];

            if (empty($exercises)) {
                $this->command->warn('L\'API a répondu avec succès mais la liste d\'exercices est vide.');
                return;
            }

            foreach ($exercises as $ex) {
                Exercise::updateOrCreate(
                    ['exerciseId' => $ex['exerciseId']], // On utilise l'ID de l'API comme référence unique
                    [
                        'name' => $ex['name'],
                        'gifUrl' => $ex['gifUrl'],
                        'targetMuscles' => $ex['targetMuscles'] ?? [],
                        'bodyParts' => $ex['bodyParts'] ?? [],
                        'equipments' => $ex['equipments'] ?? [],
                        'secondaryMuscles' => $ex['secondaryMuscles'] ?? [],
                        'instructions' => $ex['instructions'] ?? [],
                    ]
                );
            }

            $this->command->info(count($exercises) . ' exercices ont été synchronisés avec succès !');
        } else {
            $this->command->error('Échec de l\'importation.');
            $this->command->error('Statut : ' . $response->status());
            $this->command->error('Message : ' . $response->body());
        }
    }
}
