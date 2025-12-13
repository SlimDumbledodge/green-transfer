<?php

namespace App\Http\Controllers;

use App\Http\Requests\FileFilterRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\File;
use App\Models\Transfer;

class FileController extends Controller
{
    public function create(FileFilterRequest $request)
    {

        $transfer = Transfer::create([
            'uuid'       => Str::uuid(),
            'expires_at' => now()->addMinutes(1),
            'status'     => 'active',
        ]);

        $uploadedFile = $request->file('file');
        $path = $uploadedFile->store("transfers/{$transfer->uuid}", 'public');

        $file = File::create([
            'transfer_id'   => $transfer->id,
            'original_name' => $uploadedFile->getClientOriginalName(),
            'stored_path'   => $path,
            'size'          => $uploadedFile->getSize(),
            'mime_type'     => $uploadedFile->getMimeType(),
        ]);

        $url = route('transfer.view', ['uuid' => $transfer->uuid]);
        $expires_at = $transfer->expires_at->format('d/m/Y à H:i');

        return view('confirmation', compact('url', 'expires_at'));
    }

    public function viewTransfer($uuid)
    {
        $transfer = Transfer::where('uuid', $uuid)->firstOrFail();

        if ($transfer->status !== 'active' || $transfer->expires_at < now()) {
            abort(404, 'Ce transfert a expiré ou n\'est plus disponible.');
        }

        $file = File::where('transfer_id', $transfer->id)->firstOrFail();

        $filename = basename($file->stored_path);
        $downloadUrl = route('transfer.download', ['uuid' => $transfer->uuid, 'filename' => $filename]);
        $expires_at = $transfer->expires_at->format('d/m/Y à H:i');

        return view('transfer', compact('file', 'downloadUrl', 'expires_at'));
    }

    public function download($uuid, $filename)
    {
        $transfer = Transfer::where('uuid', $uuid)->firstOrFail();

        if ($transfer->status !== 'active' || $transfer->expires_at < now()) {
            abort(404, 'Ce transfert a expiré ou n\'est plus disponible.');
        }

        $file = File::where('transfer_id', $transfer->id)
                    ->where('stored_path', 'LIKE', "%{$filename}")
                    ->firstOrFail();

        if (!Storage::disk('public')->exists($file->stored_path)) {
            abort(404, 'Fichier introuvable.');
        }

        $filePath = storage_path('app/public/' . $file->stored_path);

        return response()->file($filePath, [
            'Content-Type' => $file->mime_type,
            'Content-Disposition' => 'inline; filename="' . $file->original_name . '"'
        ]);
    }
}
