<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transfert réussi</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="alternate icon" href="{{ asset('favicon.ico') }}">
    <style>
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
        .icon {
            background-color: #10b981;
            width: 80px;
            height: 80px;
            margin: 0 auto 1rem auto;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            color: #fff;
            font-size: 2rem;
        }
        h2 { margin-bottom: 0.5rem; }
        p { margin-bottom: 1rem; color: #555; font-size: 0.95rem; }
        input {
            width: 100%;
            padding: 0.5rem;
            border-radius: 6px;
            border: 1px solid #ccc;
            margin-bottom: 0.75rem;
            font-size: 0.9rem;
        }
        button {
            padding: 0.75rem 1.5rem;
            background-color: #10b981;
            color: #fff;
            border: none;
            border-radius: 8px;
            font-weight: bold;
            cursor: pointer;
            width: 100%;
        }
        button:hover { background-color: #059669; }
    </style>
</head>
<body>
    <div class="card">
        <div class="icon">✔</div>
        <h2>Félicitations !</h2>
        <p>Lien de téléchargement à partager :</p>
        <input type="text" readonly value="{{ $url }}" onclick="this.select()">
        <p>Ce lien expirera le {{ $expires_at }}</p>
        <form action="{{ url('/') }}">
            <button type="submit">Nouveau Transfert ?</button>
        </form>
    </div>
</body>
</html>
