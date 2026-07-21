<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - IMNA</title>
    <!-- On utilise CDN de Tailwind pour un rendu moderne et rapide, conforme à tes captures d'écran -->
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body class="bg-gray-900 flex items-center justify-center min-h-screen text-gray-100">

    <div class="w-full max-w-md p-8 bg-gray-800 rounded-lg shadow-xl border border-gray-700">
        
        <!-- En-tête avec l'identité de ton application -->
        <div class="text-center mb-8">
            <h1 class="text-3xl font-extrabold tracking-wider text-blue-500">IMNA</h1>
            <p class="text-xs text-gray-400 mt-1 uppercase tracking-widest">Inventory Management & Next-gen Analytics</p>
            <h2 class="text-xl font-semibold mt-4 text-gray-200">Connexion à votre espace</h2>
        </div>

        <!-- Affichage des messages d'erreur globale (ex: blocage RM-01, identifiants erronés) -->
        @if ($errors->any())
            <div class="mb-4 p-3 bg-red-900/50 border border-red-500 text-red-200 text-sm rounded-md">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Formulaire d'authentification -->
        <form action="{{ route('login') }}" method="POST" class="space-y-6">
            @csrf

            <!-- Champ Email -->
            <div>
                <label for="email" class="block text-sm font-medium text-gray-300 mb-1">Adresse Email Professionnelle</label>
                <input type="email" name="email" id="email" required autocomplete="email" value="{{ old('email') }}"
                    class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-white placeholder-gray-400"
                    placeholder="nom@entreprise.fr">
            </div>

            <!-- Champ Mot de passe -->
            <div>
                <label for="password" class="block text-sm font-medium text-gray-300 mb-1">Mot de passe</label>
                <input type="password" name="password" id="password" required autocomplete="current-password"
                    class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-white placeholder-gray-400"
                    placeholder="••••••••">
            </div>

            <!-- Bouton de validation -->
            <div>
                <button type="submit" 
                    class="w-full py-2.5 px-4 bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white font-medium rounded-md shadow-md transition duration-150 ease-in-out focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-800 focus:ring-blue-500">
                    Se connecter
                </button>
            </div>
        </form>

        <!-- Pied de page discret conforme au cadre industriel securisé -->
        <div class="mt-8 text-center text-xs text-gray-500 border-t border-gray-700 pt-4">
            Système d'Information Sécurisé IMNA &copy; 2026
        </div>
    </div>

</body>
</html>