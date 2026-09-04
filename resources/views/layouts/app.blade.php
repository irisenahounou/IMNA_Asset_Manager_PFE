<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IMNA IT Asset Manager - @yield('title')</title>
    @vite('resources/css/app.css')
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-darkbg text-gray-200 font-sans min-h-screen flex">
    <!-- Notifications (US-06 : alerte seuil de stock) -->
@if(Auth::check() && Auth::user()->estResponsable())
<div class="border-t border-darkborder pt-4 pb-4" x-data="{ ouvert: false }">
    <div class="relative">
        <button @click="ouvert = !ouvert" class="flex items-center justify-between w-full px-4 py-2 text-sm text-gray-300 hover:text-white">
            <span>🔔 Notifications</span>
            @php $nbNonLues = Auth::user()->unreadNotifications->count(); @endphp
            @if($nbNonLues > 0)
                <span class="bg-red-500 text-white text-xs font-bold rounded-full px-2 py-0.5">{{ $nbNonLues }}</span>
            @endif
        </button>
        <div x-show="ouvert" @click.outside="ouvert = false" x-cloak
             class="absolute bottom-full mb-2 left-0 w-72 bg-darkcard border border-darkborder rounded-lg shadow-xl z-50 max-h-80 overflow-y-auto">
            @forelse(Auth::user()->unreadNotifications->take(8) as $notif)
                <form method="POST" action="{{ route('notifications.lire', $notif->id) }}" class="border-b border-darkborder">
                    @csrf
                    <button type="submit" class="w-full text-left px-4 py-3 text-xs text-gray-300 hover:bg-darkborder transition">
                        ⚠️ {{ $notif->data['message'] ?? 'Nouvelle alerte' }}
                    </button>
                </form>
            @empty
                <p class="px-4 py-4 text-xs text-gray-500 text-center">Aucune nouvelle notification.</p>
            @endforelse
            @if($nbNonLues > 0)
                <form method="POST" action="{{ route('notifications.lireTout') }}" class="p-2">
                    @csrf
                    <button type="submit" class="w-full text-center text-xs text-limeacc hover:underline py-1">Tout marquer comme lu</button>
                </form>
            @endif
        </div>
    </div>
</div>
@endif

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
                @if(Auth::user()->estResponsable())
                    <a href="{{ route('dashboard') }}" class="flex items-center px-4 py-3 text-sm font-medium rounded-lg {{ request()->routeIs('dashboard') ? 'bg-limeacc text-black font-semibold' : 'text-gray-400 hover:bg-darkborder hover:text-white transition' }}">
                        📊 Dashboard
                    </a>
                    <a href="{{ route('preventives.index') }}" class="flex items-center px-4 py-3 text-sm font-medium rounded-lg text-gray-400 hover:bg-darkborder hover:text-white transition">
                       🛡️ Maintenance Préventive
                    </a>
                    <a href="{{ route('equipements.index') }}" class="flex items-center px-4 py-3 text-sm font-medium rounded-lg text-gray-400 hover:bg-darkborder hover:text-white transition">
                        📦 Parc Matériel
                    </a>
                    <a href="{{ route('composants.index') }}" class="flex items-center px-4 py-3 text-sm font-medium rounded-lg text-gray-400 hover:bg-darkborder hover:text-white transition">
                         🗄️ Gestion des Stocks
                    </a>
                    <a href="{{ route('reparations.index') }}" class="flex items-center px-4 py-3 text-sm font-medium rounded-lg text-gray-400 hover:bg-darkborder hover:text-white transition">
                        🛠️ Interventions / Pannes
                    </a>
                    <a href="{{ route('responsable.audits') }}" class="flex items-center px-4 py-3 text-sm font-medium rounded-lg {{ request()->routeIs('responsable.audits') ? 'bg-limeacc text-black font-semibold' : 'text-gray-400 hover:bg-darkborder hover:text-white transition' }}">
                        <svg class="w-5 h-5 mr-3 {{ request()->routeIs('responsable.audits') ? 'text-black' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                        </svg>
                        Journal d'Audit
                    </a>
                    <a href="{{ route('dashboard.usure') }}" class="flex items-center px-4 py-3 text-sm font-medium rounded-lg {{ request()->routeIs('dashboard.usure') ? 'bg-limeacc text-black font-semibold' : 'text-gray-400 hover:bg-darkborder hover:text-white transition' }}">
                        <svg class="w-5 h-5 mr-3 {{ request()->routeIs('dashboard.usure') ? 'text-black' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                        <span>Analyse d'Usure du Parc</span>
                    </a>
                    <a href="{{ route('utilisateurs.index') }}" class="flex items-center px-4 py-3 text-sm font-medium rounded-lg {{ request()->routeIs('utilisateurs.*') ? 'bg-limeacc text-black font-semibold' : 'text-gray-400 hover:bg-darkborder hover:text-white transition' }}">
                        🔐 Accès Mobiles
                    </a>
                @elseif(Auth::user()->estTechnicien())
                    <a href="{{ route('technicien.dashboard') }}" class="flex items-center px-4 py-3 text-sm font-medium rounded-lg {{ request()->routeIs('technicien.dashboard') ? 'bg-limeacc text-black font-semibold' : 'text-gray-400 hover:bg-darkborder hover:text-white transition' }}">
                        🛠️ Mes Interventions
                    </a>
                @else
                    <a href="{{ route('employe.dashboard') }}" class="flex items-center px-4 py-3 text-sm font-medium rounded-lg {{ request()->routeIs('employe.dashboard') ? 'bg-limeacc text-black font-semibold' : 'text-gray-400 hover:bg-darkborder hover:text-white transition' }}">
                        📊 Dashboard
                    </a>
                @endif
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