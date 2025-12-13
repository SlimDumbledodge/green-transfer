<?php

namespace App\Console\Commands;

use App\Models\File;
use App\Models\Transfer;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class DeleteExpiredFiles extends Command
{
    protected $signature = 'files:cleanup';
    protected $description = 'Supprime les fichiers expirés du système de transfert';

    public function handle()
    {
        $now = Carbon::now();

        // Correction ici : "where" au lieu de "were"
        $expiredTransfers  = Transfer::with('files')->where('expires_at', '<', $now)->get();
        $totalFilesDeleted = 0;
        foreach ($expiredTransfers as $transfer) {
            foreach ($transfer->files as $file) {
                // Vérifier et supprimer le fichier physique
                if (!empty($file->stored_path) && Storage::disk('public')->exists($file->stored_path)) {
                    Storage::disk('public')->delete($file->stored_path);
                    $this->info("Fichier supprimé : {$file->stored_path}");
                }
                $file->delete();
                $totalFilesDeleted++;
            }

            // Supprimer le dossier du transfert s'il est vide
            $transferDir = "transfers/{$transfer->uuid}";
            if (Storage::disk('public')->exists($transferDir)) {
                Storage::disk('public')->deleteDirectory($transferDir);
            }

            $transfer->delete();
        }

        $this->info(count($expiredTransfers) . ' transferts supprimés, ' . $totalFilesDeleted . ' fichiers supprimés.');
    }
}
