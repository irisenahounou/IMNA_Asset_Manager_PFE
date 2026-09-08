@extends('layouts.app')

@section('title', 'Déclarer une panne')
@section('role-badge', 'Responsable')

@section('content')
<div class="flex justify-center items-center min-h-[80vh] w-full">
    <div class="w-full max-w-md mx-auto">

        <h1 class="text-white font-bold mb-8 text-center text-3xl tracking-tight">
            Signalez un Incident
        </h1>

        @if ($errors->any())
            <div class="mb-4 p-4 bg-red-950 border border-red-800 text-red-300 text-sm rounded-lg">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('pannes.store') }}" method="POST" class="space-y-4">
            @csrf

            <input type="hidden" name="titre" value="Incident matériel">

            <div>
                <select name="id_materiel" required class="w-full bg-darkcard border border-darkborder rounded-lg py-3 px-3 text-sm text-white focus:outline-none focus:border-limeacc">
                    <option value="" disabled selected>Sélectionnez l'équipement (ex: Imprimante, PC...)</option>
                    @foreach($materiels as $materiel)
                        <option value="{{ $materiel->id }}" {{ old('id_materiel') == $materiel->id ? 'selected' : '' }}>
                            {{ $materiel->nom_equipement }} (ID: {{ $materiel->id }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <select name="id_employe" required class="w-full bg-darkcard border border-darkborder rounded-lg py-3 px-3 text-sm text-white focus:outline-none focus:border-limeacc">
                    <option value="" disabled selected>Sélectionnez l'employé concerné</option>
                    @foreach($employes as $employe)
                        <option value="{{ $employe->id_employe }}" {{ old('id_employe') == $employe->id_employe ? 'selected' : '' }}>
                            {{ $employe->prenom }} {{ $employe->nom }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <textarea name="description" rows="4" required placeholder="Décrivez le problème rencontré en quelques mots..."
                    class="w-full bg-darkcard border border-darkborder rounded-lg py-3 px-3 text-sm text-white focus:outline-none focus:border-limeacc resize-none">{{ old('description') }}</textarea>
            </div>

            <button type="submit" class="w-full bg-limeacc hover:bg-lime-400 text-black font-bold py-3 rounded-lg text-sm transition">
                Soumettre la déclaration
            </button>
        </form>

    </div>
</div>
@endsection