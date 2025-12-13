<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GreenTransfer - Télécharger le fichier</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="alternate icon" href="{{ asset('favicon.ico') }}">
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f0f2f5;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .card {
            background-color: #fff;
            padding: 2rem;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 500px;
        }

        h2 {
            color: #333;
            margin-bottom: 1.5rem;
            text-align: center;
        }

        .file-info {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background-color: #f8fafc;
            padding: 1.5rem;
            border-radius: 8px;
            border: 2px solid #e5e7eb;
            margin-bottom: 1rem;
        }

        .file-details {
            flex: 1;
            margin-right: 1rem;
        }

        .file-name {
            font-weight: bold;
            color: #1f2937;
            font-size: 1.1rem;
            margin-bottom: 0.5rem;
            word-break: break-word;
        }

        .file-meta {
            color: #6b7280;
            font-size: 0.875rem;
        }

        .download-btn {
            background-color: #10b981;
            color: white;
            border: none;
            padding: 1rem 1.5rem;
            border-radius: 8px;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 1rem;
            font-weight: bold;
            text-decoration: none;
            transition: background-color 0.2s;
        }

        .download-btn:hover {
            background-color: #059669;
        }

        .download-icon {
            width: 24px;
            height: 24px;
        }

        .expires-info {
            text-align: center;
            color: #6b7280;
            font-size: 0.875rem;
            margin-top: 1rem;
            padding-top: 1rem;
            border-top: 1px solid #e5e7eb;
        }

        .privacy-footer {
            background-color: #f8fafc;
            border-radius: 8px;
            padding: 1rem;
            margin-top: 1.5rem;
            font-size: 0.8rem;
            color: #6b7280;
            text-align: center;
            border: 1px solid #e5e7eb;
        }

        .expires-info strong {
            color: #dc2626;
        }
    </style>
</head>
<body>
    <div class="card">
        <h2>📦 Fichier prêt au téléchargement</h2>

        <div class="file-info">
            <div class="file-details">
                <div class="file-name">{{ $file->original_name }}</div>
                <div class="file-meta">
                    @php
                        $bytes = $file->size;
                        $units = ['o', 'Ko', 'Mo', 'Go', 'To'];
                        $i = 0;
                        while ($bytes > 1024 && $i < count($units) - 1) {
                            $bytes /= 1024;
                            $i++;
                        }
                        $formattedSize = round($bytes, 2) . ' ' . $units[$i];
                    @endphp
                    {{ $formattedSize }} • {{ $file->mime_type }}
                </div>
            </div>

            <a href="{{ $downloadUrl }}" class="download-btn" download="{{ $file->original_name }}">
                <svg class="download-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                </svg>
                Télécharger
            </a>
        </div>

        <div class="expires-info">
            Ce lien expire le <strong>{{ $expires_at }}</strong>
        </div>
        <div class="privacy-footer">
            🔒 <strong>Confidentialité :</strong> Ce fichier est hébergé de manière sécurisée. Aucune donnée n'est partagée avec des tiers et sera automatiquement supprimée après expiration.
        </div>    </div>
</body>
</html>
