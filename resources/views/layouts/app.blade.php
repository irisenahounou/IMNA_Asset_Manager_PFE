<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IMNA IT Asset Manager - @yield('title')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        darkbg: '#0f0f12',
                        darkcard: '#18181c',
                        darkborder: '#27272a',
                        limeacc: '#bef264', // Vert pistache / lime Figma
                        limetext: '#d9f99d'
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-darkbg text-gray-200 font-sans min-h-screen flex">

    <!-- Sidebar Gauche Figma -->
    <aside class="w-64 bg-darkcard border-r border-darkborder flex flex-col justify-between p-6">
        <div>
            <!-- Logo / Brand -->
            <div class="mb-10">
                <h1 class="text-xl font-bold text-white tracking-wide">IMNA <span class="text-limeacc">Asset Manager</span></h1>
                <p class="text-xs text-gray-400 mt-1">Inventory & Maintenance</p>
            </div>

            <!-- Navigation -->
            <nav class="space-y-2">
                <a href="#" class="flex items-center px-4 py-3 text-sm font-medium rounded-lg bg-limeacc text-black font-semibold">
                    📊 Dashboard
                </a>
                <a href="{{ route('preventives.index') }}" class="flex items-center px-4 py-3 text-sm font-medium rounded-lg text-gray-400 hover:bg-darkborder hover:text-white transition">
                   🛡️ Maintenance Préventive
                </a>
                <a href="{{ route('equipements.index') }}" class="flex items-center px-4 py-3 text-sm font-medium rounded-lg text-gray-400 hover:bg-darkborder hover:text-white transition">
                    📦 Parc Matériel
                </a>
                <a href="{{ route('reparations.index') }}" class="flex items-center px-4 py-3 text-sm font-medium rounded-lg text-gray-400 hover:bg-darkborder hover:text-white transition">
                    🛠️ Interventions / Pannes
                </a>
            </nav>
        </div>

        <!-- Profil & Déconnexion -->
        <div class="border-t border-darkborder pt-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-semibold text-white">{{ Auth::user()->prenom ?? Auth::user()->nom ?? 'Utilisateur' }}</p>
                    <p class="text-xs text-gray-400">@yield('role-badge', 'Session active')</p>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" title="Déconnexion" class="text-gray-400 hover:text-red-400 text-lg">
                        🚪
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <!-- Zone de Contenu Principal -->
    <main class="flex-1 p-8 overflow-y-auto">
        @yield('content')
    </main>

</body>
</html>