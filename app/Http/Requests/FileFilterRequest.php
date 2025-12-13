<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FileFilterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $allowedMimes = [
            // Documents
            'application/pdf',
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'application/vnd.ms-excel',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'application/vnd.ms-powerpoint',
            'application/vnd.openxmlformats-officedocument.presentationml.presentation',
            'text/plain',
            'text/csv',
            // Images
            'image/jpeg',
            'image/png',
            'image/gif',
            'image/webp',
            'image/svg+xml',
            // Archives
            'application/zip',
            'application/x-rar-compressed',
            'application/x-7z-compressed',
            'application/x-tar',
            'application/gzip',
            // Audio/Video
            'audio/mpeg',
            'audio/wav',
            'audio/ogg',
            'video/mp4',
            'video/mpeg',
            'video/quicktime',
            'video/x-msvideo',
        ];

        $allowedExtensions = [
            'pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'txt', 'csv',
            'jpg', 'jpeg', 'png', 'gif', 'webp', 'svg',
            'zip', 'rar', '7z', 'tar', 'gz',
            'mp3', 'wav', 'ogg', 'mp4', 'mpeg', 'mov', 'avi'
        ];

        return [
            'file' => [
                'required',
                'file',
                'max:51200', // 50 Mo
                'mimetypes:' . implode(',', $allowedMimes),
                function ($attribute, $value, $fail) use ($allowedExtensions) {
                    $extension = strtolower($value->getClientOriginalExtension());
                    if (!in_array($extension, $allowedExtensions)) {
                        $fail("Le type de fichier .{$extension} n'est pas autorisé.");
                    }
                },
                function ($attribute, $value, $fail) {
                    $filename = $value->getClientOriginalName();
                    if (preg_match('/[^a-zA-Z0-9._\-éèêëàâäôöùûüïîçÉÈÊËÀÂÄÔÖÙÛÜÏÎÇ ]/', $filename)) {
                        $fail("Le nom du fichier contient des caractères non autorisés.");
                    }

                    if (preg_match('/\.(php|phtml|php3|php4|php5|phar|exe|sh|bat|cmd)($|\.)/i', $filename)) {
                        $fail("Le fichier contient une extension interdite.");
                    }
                },
            ],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'file.required' => 'Veuillez sélectionner un fichier à transférer.',
            'file.file' => 'Le fichier téléchargé n\'est pas valide.',
            'file.max' => 'Le fichier ne doit pas dépasser 50 Mo.',
            'file.mimes' => 'Le type de fichier n\'est pas autorisé. Formats acceptés : PDF, documents Office, images, archives, audio et vidéo.',
        ];
    }
}
