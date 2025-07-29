@php
    $nomDossier ??= '';
    $nbDocument ??= 0;
    $nbProjet ??= 0;
    $idDossier ??= '';
    $idProjet ??= '';
@endphp

@foreach ($dossiers as $d)
    <tr class="hover:bg-gray-50">
        <td>
            <i class="fa-solid fa-folder text-yellow-500 mr-2"></i>{{ $d->nomDossier }}
        </td>
        <td class="text-right">{{ $d->nombreDocument ?? '' }}</td>
        <td class="text-right">{{ $d->nombreProjet ?? '' }}</td>
        <td>
            <div onclick="ajoutProjetInFolderStepper({{ $d->idDossier }}, {{ $idProjet }})"
                class="icon w-10 h-10 rounded-full flex items-center justify-center bg-green-100 cursor-pointer hover:bg-green-50 group/icon duration-150">
                <i class="fa-solid fa-plus text-green-500 text-sm group-hover/icon:text-green-600 duration-150"></i>
            </div>
        </td>
    </tr>
@endforeach
