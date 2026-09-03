<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - IMNA</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-darkbg flex items-center justify-center min-h-screen text-gray-200 font-sans">

    <div class="w-full max-w-md px-8">

        <!-- En-tête avec l'identité de ton application -->
        <div class="text-center mb-10">
            <h1 class="text-4xl font-extrabold tracking-wide text-white">
                IMNA <span class="text-limeacc">IT Asset Manager</span>
            </h1>
            <p class="text-xs text-gray-400 mt-2 uppercase tracking-widest">Inventory Management &amp; Next-gen Analytics</p>
        </div>

        <!-- Affichage des messages d'erreur globale (ex: blocage RM-01, identifiants erronés) -->
        @if ($errors->any())
            <div class="mb-4 p-3 bg-red-950 border border-red-800 text-red-300 text-sm rounded-md">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Formulaire d'authentification -->
        <form action="{{ route('login') }}" method="POST" class="space-y-5">
            @csrf

            <!-- Champ Email -->
            <div>
                <input type="email" name="email" id="email" required autocomplete="email" value="{{ old('email') }}"
                    class="w-full px-4 py-3 bg-darkcard border border-darkborder rounded-md focus:outline-none focus:ring-2 focus:ring-limeacc focus:border-transparent text-white placeholder-gray-500"
                    placeholder="Adresse email">
            </div>

            <!-- Champ Mot de passe -->
            <div>
                <input type="password" name="password" id="password" required autocomplete="current-password"
                    class="w-full px-4 py-3 bg-darkcard border border-darkborder rounded-md focus:outline-none focus:ring-2 focus:ring-limeacc focus:border-transparent text-white placeholder-gray-500"
                    placeholder="Mot de passe">
            </div>

            <!-- Bouton de validation -->
            <div class="pt-2">
                <button type="submit"
                    class="w-full py-3 px-4 bg-limeacc hover:opacity-90 active:opacity-80 text-black font-semibold rounded-md shadow-md transition duration-150 ease-in-out">
                    Se connecter
                </button>
            </div>

            <div class="text-center">
                <a href="#" class="text-xs text-gray-500 hover:text-gray-300">Mot de passe oublié</a>
            </div>
        </form>

        <!-- Pied de page discret conforme au cadre industriel sécurisé -->
        <div class="mt-10 text-center text-xs text-gray-600 border-t border-darkborder pt-4">
            Système d'Information Sécurisé IMNA &copy; 2026
        </div>
    </div>

</body>
</html>