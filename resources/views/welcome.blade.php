<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IMNA IT Asset Manager</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-darkbg flex items-center justify-center min-h-screen text-gray-200 font-sans">

    <div class="text-center px-6">
        <h1 class="text-5xl font-extrabold tracking-wide text-white mb-3">
            IMNA <span class="text-limeacc">IT Asset Manager</span>
        </h1>
        <p class="text-sm text-gray-400 uppercase tracking-widest mb-10">
            Inventory Management &amp; Next-gen Analytics
        </p>

        <p class="text-gray-400 max-w-md mx-auto mb-10 text-sm leading-relaxed">
            Plateforme de gestion de maintenance de parc informatique pour PME —
            supervision Web pour le DSI, intervention terrain via l'application mobile
            pour les techniciens et employés.
        </p>

        @if (Route::has('login'))
            <a href="{{ route('login') }}"
               class="inline-block bg-limeacc hover:bg-lime-400 text-black font-semibold px-8 py-3 rounded-lg text-sm transition">
                Se connecter
            </a>
        @endif

        <p class="mt-16 text-xs text-gray-600">
            Système d'Information Sécurisé IMNA &copy; 2026
        </p>
    </div>

</body>
</html>