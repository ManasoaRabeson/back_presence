@extends('layouts.masterAdmin')

@section('content')
    <div class="w-full h-full flex flex-col gap-4 container mx-auto">
        <div class="row">
            <div class="col-8">
                <h1>Villes et code postal</h1>

                @if (Session::has('success'))
                    <p style="background: lightgreen">{{ Session('success') }}</p>
                @elseif (Session::has('error'))
                    <p style="background: red">{{ Session('error') }}</p>
                @endif

                @if (count($villes) > 0)
                    <table class="table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>code postal</th>
                                <th>région</th>
                                <th>lieu</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $i = 1;
                            @endphp
                            @foreach ($villes as $v)
                                <tr>
                                    <td>{{ $i++ }}</td>
                                    <td>{{ $v->vi_code_postal }}</td>
                                    <td>{{ $v->ville }}</td>
                                    <td>{{ $v->ville_name }}</td>
                                    <td>
                                        <form action="{{ route('superAdmin.villes.destroy', $v->idVille) }}" method="post">
                                            @csrf
                                            @method('delete')

                                            <button type="submit"><i class="fa-solid fa-trash-can"></i></button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <p>Aucun résultat</p>
                @endif
            </div>
            <div class="col-4">
                <form action="{{ route('superAdmin.villes.store') }}" method="post">
                    @csrf

                    <div class="mb-3">
                        <label for="idVille" class="form-label">Région</label>
                        <select name="idVille" id="idVille" class="form form-control form-sm">
                            @foreach ($vls as $vl)
                                <option value="{{ $vl->idVille }}">{{ $vl->ville }}</option>
                            @endforeach
                        </select>
                        @error('idVille')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="ville_name" class="form-label">Lieu</label>
                        <input type="text" class="form-control @error('ville_name') is-invalid @enderror" id="ville"
                            value="{{ old('ville_name') }}" name="ville_name" placeholder="ex: Tana">
                        @error('ville_name')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="vi_code_postal" class="form-label">Code postal</label>
                        <input type="number" class="form-control @error('vi_code_postal') is-invalid @enderror"
                            id="vi_code_postal" value="{{ old('vi_code_postal') }}" name="vi_code_postal"
                            placeholder="ex: 101">
                        @error('vi_code_postal')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-success">Ajouter</button>
                </form>

                <h1 class="text-lg font-semibold text-gray-700 mt-4">Pour faire un import des données</h1>
                <form action="{{ route('importer.import') }}" method="post" enctype="multipart/form-data"
                    class="space-y-6">
                    @csrf

                    <!-- Sélection de la région -->
                    <div class="flex flex-col space-y-2">
                        <label for="region" class="text-lg font-semibold text-gray-700">Choisir une région :</label>
                        <select name="region" id="region" required
                            class="p-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @foreach ($vls as $vl)
                                <option value="{{ $vl->idVille }}">{{ $vl->ville }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Sélection du fichier Excel -->
                    <div class="flex flex-col space-y-2">
                        <label for="excel_file" class="text-lg font-semibold text-gray-700">Choisir un fichier Excel
                            :</label>
                        <input type="file" name="excel_file" id="excel_file" accept=".xls, .xlsx" required
                            class="p-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <!-- Bouton de soumission -->
                    <div>
                        <button type="submit"
                            class="w-full py-3 bg-green-500 text-white font-semibold rounded-lg shadow-md hover:bg-green-600 transition duration-200 ease-in-out">
                            Importer les données
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
@endsection

@section('script')
@endsection
