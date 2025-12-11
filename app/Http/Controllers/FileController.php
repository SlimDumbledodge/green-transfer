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
        $url = Storage::url($file->stored_path);
        $expires_at = $transfer->expires_at->format('d/m/Y à H:i');

        return view('confirmation', compact('url', 'expires_at'));

    }
}
