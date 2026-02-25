<?php

namespace App\Console\Commands;

use App\Models\Exercise;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class ImportExercises extends Command
{
    protected $signature = 'exercises:import {--limit=1300 : Nombre maximum d\'exercices à importer} {--offset=0 : Offset de départ}';

    protected $description = 'Importe les exercices depuis l\'API ExerciseDB (exercisedb.dev)';

    public function handle(): int
    {
        $limit = (int) $this->option('limit');
        $offset = (int) $this->option('offset');
        $batchSize = 100;
        $imported = 0;
        $errors = 0;

        $this->info("Importation de {$limit} exercices depuis ExerciseDB...");
        $bar = $this->output->createProgressBar(ceil($limit / $batchSize));
        $bar->start();

        while ($imported < $limit) {
            $currentBatch = min($batchSize, $limit - $imported);

            try {
                $response = Http::timeout(30)
                    ->withHeaders(['Accept' => 'application/json'])
                    ->get('https://exercisedb.dev/api/v1/exercises', [
                        'limit'  => $currentBatch,
                        'offset' => $offset + $imported,
                    ]);

                if (!$response->successful()) {
                    $this->newLine();
                    $this->error("Erreur API (status {$response->status()}) à offset " . ($offset + $imported));
                    $errors++;
                    break;
                }

                $result = $response->json();
                $exercises = $result['data'] ?? $result ?? [];

                if (empty($exercises)) {
                    break; // Plus d'exercices disponibles
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

                // Pause pour éviter le rate limiting
                if ($imported < $limit) {
                    usleep(200000); // 200ms
                }

            } catch (\Exception $e) {
                $this->newLine();
                $this->error("Exception : " . $e->getMessage());
                $errors++;
                break;
            }
        }

        $bar->finish();
        $this->newLine();

        $total = Exercise::count();
        $this->info("{$imported} exercices importés. Total en base : {$total}");

        if ($errors > 0) {
            $this->warn("{$errors} erreur(s) rencontrée(s).");
        }

        return $errors > 0 ? self::FAILURE : self::SUCCESS;
    }
}
