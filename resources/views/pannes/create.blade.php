@extends('layouts.app')

@section('content')
<div style="display: flex; justify-content: center; align-items: center; min-height: 80vh; width: 100%;">
    <div style="width: 100%; max-width: 480px; margin: 0 auto;">
        
        {{-- Titre centré --}}
        <h1 class="text-white fw-bold mb-4 text-center" style="font-size: 2.2rem; letter-spacing: -0.5px;">
            Signalez un Incident
        </h1>

        {{-- Message d'erreur de validation --}}
        @if ($errors->any())
            <div class="alert alert-danger mb-4" style="background-color: #3a1c1c; border: 1px solid #5a2424; color: #f87171; border-radius: 10px;">
                <ul class="mb-0 ps-3">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('pannes.store') }}" method="POST">
            @csrf

            {{-- Titre par défaut (nécessaire pour la BDD) --}}
            <input type="hidden" name="titre" value="Incident matériel">

            {{-- Menu déroulant équipement --}}
            <div class="mb-3">
                <select name="id_materiel" class="form-select text-white border-0 py-3 px-3 w-100" style="background-color: #242526; border-radius: 10px; font-size: 0.95rem;" required>
    <option value="" disabled selected>Sélectionnez l'équipement (ex: Imprimante, PC...)</option>
    @foreach($materiels as $materiel)
        <option value="{{ $materiel->id }}" {{ old('id_materiel') == $materiel->id ? 'selected' : '' }}>
            {{ $materiel->nom_equipement }} (ID: {{ $materiel->id }})
        </option>
    @endforeach
                </select>
            </div>

            {{-- Identifiant Employé --}}
            <div class="mb-3">
                <input type="number" name="id_employe" class="form-control text-white border-0 py-3 px-3 w-100" style="background-color: #242526; border-radius: 10px; font-size: 0.95rem;" value="{{ old('id_employe', auth()->user()->id_utilisateur ?? 1) }}" placeholder="Identifiant employé" required>
            </div>

            {{-- Zone de texte description --}}
            <div class="mb-4">
                <textarea name="description" rows="4" class="form-control text-white border-0 py-3 px-3 w-100" style="background-color: #242526; border-radius: 10px; font-size: 0.95rem; resize: none;" placeholder="Décrivez le problème rencontré en quelques mots..." required>{{ old('description') }}</textarea>
            </div>

            {{-- Bouton Vert Néon Figma --}}
            <button type="submit" class="btn w-100 fw-bold py-3 text-dark" style="background-color: #d4f570; border-radius: 10px; border: none; font-size: 1rem;">
                soumettre la declaration
            </button>
        </form>

    </div>
</div>
@endsection