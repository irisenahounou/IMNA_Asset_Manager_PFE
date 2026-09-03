<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

#[Signature('app:analyser-usure-materiel')]
#[Description('Command description')]
class AnalyserUsureMateriel extends Command
{
    protected $signature = 'imna:analyser-usure';
    protected $description = 'Exécute l\'analyse prédictive Python sur l\'usure du matériel';
    public function handle()
    {
        $this->info('Lancement de l\'analyse prédictive Python...');
        // Récupère le chemin réel de la base configurée dans .env (portable,
        // fonctionne quel que soit l'emplacement du projet sur la machine).
       $dbPath = config('database.connections.sqlite.database');
       $process = new Process(['python3', base_path('app/Python/predict_wear.py'), $dbPath]);
       $process->run();

       if (!$process->isSuccessful()) {
        throw new ProcessFailedException($process);
    
       }
       $output = $process->getOutput();
       $data = json_decode($output, true);

       if (isset($data['error'])) {
        $this->error("Erreur Python : " . $data['error']);
        return;
       }
       // Affiche un joli tableau dans la console pour test

       $this->table ([
        'ID', 'Nom du Matériel', 'Pannes', 'Score d\'Usure', 'Recommandation'
       ],
       array_map(function ($item) {
        return [
            $item['id_materiel'],
            $item['nom_equipement'],
            $item['nombre_pannes'],
            $item['score_usure'] . '%',
            $item['recommandation']
        ];
       }, $data)
       );
       $this->info('Analyse prédictive terminée avec succès !');
    }
}
