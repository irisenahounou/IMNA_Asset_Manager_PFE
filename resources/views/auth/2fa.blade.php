<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IMNA - Validation 2FA</title>
    <style>
        body { font-family: system-ui, -apple-system, sans-serif; background: #f3f4f6; display: flex; align-items: center; justify-content: center; height: 100vh; margin: 0; }
        .card { background: white; padding: 2rem; border-radius: 8px; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); width: 100%; max-width: 400px; }
        h1 { font-size: 1.25rem; font-weight: 600; margin-bottom: 0.5rem; text-align: center; }
        p { font-size: 0.875rem; color: #4b5563; text-align: center; margin-bottom: 1.5rem; }
        input[type="text"] { width: 100%; padding: 0.75rem; border: 1px solid #d1d5db; border-radius: 6px; font-size: 1.5rem; text-align: center; letter-spacing: 0.25rem; box-sizing: border-box; }
        button { width: 100%; padding: 0.75rem; background: #2563eb; color: white; border: none; border-radius: 6px; font-weight: 600; margin-top: 1rem; cursor: pointer; }
        button:hover { background: #1d4ed8; }
        .error { color: #dc2626; font-size: 0.875rem; margin-top: 0.5rem; text-align: center; }
        .resend { text-align: center; margin-top: 1rem; }
        .resend button { background: none; color: #4b5563; border: none; text-decoration: underline; font-size: 0.875rem; cursor: pointer; width: auto; margin: 0; padding: 0; }
    </style>
</head>
<body>
    <div class="card">
        <h1>Authentification à deux facteurs</h1>
        <p>Un code de vérification à 6 chiffres a été généré.</p>

        <form method="POST" action="{{ route('2fa.verify') }}">
            @csrf
            <div>
                <input type="text" name="code" placeholder="000000" maxlength="6" autofocus required autocomplete="off">
            </div>

            @error('code')
                <div class="error">{{ $message }}</div>
            @enderror

            <button type="submit">Valider le code</button>
        </form>

        <form method="POST" action="{{ route('2fa.resend') }}" class="resend">
            @csrf
            <button type="submit">Renvoyer un nouveau code</button>
        </form>
    </div>
</body>
</html>