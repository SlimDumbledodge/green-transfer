<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\File;
use App\Models\Transfer;

class FileController extends Controller
{
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|file|max:10240', // max 10 Mo
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed.',
                'errors'  => $validator->errors(),
            ], 422);
        }

        // Création du transfert
        $transfer = Transfer::create([
            'uuid'       => Str::uuid(),
            'expires_at' => now()->addDays(7),
            'status'     => 'active',
        ]);

        // Upload et stockage du fichier
        $uploadedFile = $request->file('file');
        $path = $uploadedFile->store("transfers/{$transfer->uuid}", 'public');

        // Enregistrement en base
        $file = File::create([
            'transfer_id'   => $transfer->id,
            'original_name' => $uploadedFile->getClientOriginalName(),
            'stored_path'   => $path,
            'size'          => $uploadedFile->getSize(),
            'mime_type'     => $uploadedFile->getMimeType(),
        ]);

        // Après avoir créé le fichier et le transfert
        $filename = basename($file->stored_path);
        $url = route('transfer.show', ['uuid' => $transfer->uuid, 'filename' => $filename]);
        $expires_at = $transfer->expires_at->format('d/m/Y à H:i');

        return view('confirmation', compact('url', 'expires_at'));
    }

    public function show($uuid, $filename)
    {
        // Récupérer le transfert
        $transfer = Transfer::where('uuid', $uuid)->firstOrFail();
        
        // Vérifier si le transfert est toujours actif
        if ($transfer->status !== 'active' || $transfer->expires_at < now()) {
            abort(404, 'Ce transfert a expiré ou n\'est plus disponible.');
        }

        // Récupérer le fichier
        $file = File::where('transfer_id', $transfer->id)
                    ->where('stored_path', 'LIKE', "%{$filename}")
                    ->firstOrFail();

        // Vérifier que le fichier existe dans le storage
        if (!Storage::disk('public')->exists($file->stored_path)) {
            abort(404, 'Fichier introuvable.');
        }

        // Retourner le fichier
        $filePath = storage_path('app/public/' . $file->stored_path);
        
        return response()->file($filePath, [
            'Content-Type' => $file->mime_type,
            'Content-Disposition' => 'inline; filename="' . $file->original_name . '"'
        ]);
    }
}
