<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GreenTransfer</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="alternate icon" href="{{ asset('favicon.ico') }}">
    <style>
        /* Reset minimal */
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
            max-width: 400px;
            text-align: center;
        }

        h2 {
            margin-bottom: 1.5rem;
            color: #333;
        }

        input[type="file"] {
            display: none; /* on cache l'input de base */
        }

        .file-label {
            display: inline-block;
            padding: 0.75rem 1.5rem;
            background-color: #4f46e5;
            color: #fff;
            border-radius: 8px;
            cursor: pointer;
            font-weight: bold;
            transition: background-color 0.2s ease;
        }

        .file-label:hover {
            background-color: #3730a3;
        }

        button[type="submit"] {
            margin-top: 1rem;
            padding: 0.75rem 1.5rem;
            background-color: #10b981;
            color: #fff;
            border: none;
            border-radius: 8px;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.2s ease;
        }

        button[type="submit"]:hover {
            background-color: #059669;
        }

        .file-name {
            margin-top: 0.75rem;
            font-size: 0.9rem;
            color: #555;
            word-break: break-all;
        }

        .loader-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.7);
            z-index: 9999;
            justify-content: center;
            align-items: center;
            flex-direction: column;
        }

        .loader-overlay.active {
            display: flex;
        }

        .spinner {
            width: 50px;
            height: 50px;
            border: 5px solid #f3f3f3;
            border-top: 5px solid #10b981;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .loader-text {
            color: white;
            margin-top: 1rem;
            font-size: 1.1rem;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <!-- Loader overlay -->
    <div class="loader-overlay" id="loaderOverlay">
        <div class="spinner"></div>
        <div class="loader-text">Téléchargement en cours...</div>
    </div>

    <div class="card">
        <h2>Ajouter un fichier</h2>

        <form action="{{ url('/file') }}" method="POST" enctype="multipart/form-data" id="uploadForm">
            @csrf

            <!-- Label stylé pour input file -->
            <label class="file-label" for="fileInput">Choisir un fichier</label>
            <input type="file" id="fileInput" name="file" required>

            <!-- Affiche le nom du fichier sélectionné -->
            <div class="file-name" id="fileName">Aucun fichier choisi</div>

            <button type="submit">Envoyer</button>
        </form>
    </div>

    <script>
        const fileInput = document.getElementById('fileInput');
        const fileName = document.getElementById('fileName');
        const uploadForm = document.getElementById('uploadForm');
        const loaderOverlay = document.getElementById('loaderOverlay');

        fileInput.addEventListener('change', () => {
            if (fileInput.files.length > 0) {
                fileName.textContent = fileInput.files[0].name;
            } else {
                fileName.textContent = "Aucun fichier choisi";
            }
        });

        uploadForm.addEventListener('submit', () => {
            loaderOverlay.classList.add('active');
        });
    </script>
</body>
</html>
