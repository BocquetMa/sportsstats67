<?php

namespace Database\Seeders;

use App\Models\Exercise;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;

class ExerciseSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('Importation des exercices depuis ExerciseDB...');

        $limit = 1300;
        $batchSize = 100;
        $imported = 0;

        $bar = $this->command->getOutput()->createProgressBar(ceil($limit / $batchSize));
        $bar->start();

        while ($imported < $limit) {
            $currentBatch = min($batchSize, $limit - $imported);

            try {
                $response = Http::timeout(30)
                    ->withHeaders(['Accept' => 'application/json'])
                    ->get('https://exercisedb.dev/api/v1/exercises', [
                        'limit'  => $currentBatch,
                        'offset' => $imported,
                    ]);

                if (!$response->successful()) {
                    $this->command->newLine();
                    $this->command->error("Erreur API status {$response->status()}");
                    break;
                }

                $result = $response->json();
                $exercises = $result['data'] ?? $result ?? [];

                if (empty($exercises)) {
                    break;
                }

                foreach ($exercises as $ex) {
                    Exercise::updateOrCreate(
                        ['exerciseId' => $ex['exerciseId'] ?? $ex['id'] ?? uniqid()],
                        [
                            'name'             => $ex['name'],
                            'gifUrl'           => $ex['gifUrl'] ?? null,
                            'targetMuscles'    => $ex['targetMuscles'] ?? [],
                            'bodyParts'        => $ex['bodyParts'] ?? (isset($ex['bodyPart']) ? [$ex['bodyPart']] : []),
                            'equipments'       => $ex['equipments'] ?? (isset($ex['equipment']) ? [$ex['equipment']] : []),
                            'secondaryMuscles' => $ex['secondaryMuscles'] ?? [],
                            'instructions'     => $ex['instructions'] ?? [],
                        ]
                    );
                }

                $imported += count($exercises);
                $bar->advance();
                usleep(200000); // 200ms pour éviter le rate limiting

            } catch (\Exception $e) {
                $this->command->newLine();
                $this->command->error("Exception : " . $e->getMessage());
                break;
            }
        }

        $bar->finish();
        $this->command->newLine();
        $this->command->info(Exercise::count() . ' exercices disponibles en base.');
    }
}
