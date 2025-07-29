@php
    $titre ??= 'Annuler';
    $description ??= 'Voulez-vous vraiment annuler cette session ?';
    $onClick ??= '';
    $addNbPlace ??= '';
    $id ??= '';
@endphp

<div class="du_modal-box">
    <h3 class="text-lg font-bold">{{ $titre }}!</h3>
    <p class="py-2">{{ $description }}</p>
    @if ($addNbPlace == 'on')
        <label class="w-full">
            <div class="label">
                <span class="label-text">Entrer le nombre de places disponibles</span>
            </div>
            <input type="number" name="nbPlace" id="nbPlace_{{ $id }}" class="input input-bordered w-full" />
        </label>
    @endif
    <div class="du_modal-action">
        <form method="dialog">
            <button class="btn">Non, annuler</button>
            <button class="btn btn-primary text-white ml-3" onclick="{{ $onClick }}">Oui, je
                confirme</button>
        </form>
    </div>
</div>
