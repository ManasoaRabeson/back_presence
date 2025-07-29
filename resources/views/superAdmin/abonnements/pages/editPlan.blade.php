@extends('layouts.masterAdmin')

@section('content')
<div class="flex flex-col w-full">
    <div>
        <x-sub-super icon="money-check-dollar" label="Modifier un PLAN">
        <x-v-separator />
        </x-sub-super>
    </div>
    <div class="container mt-10">
    <form action="{{ route('crudAbn.update', $plans->id) }}" method="POST">
        @csrf
        @method('PATCH')
        <div id="planForm">
            <input type="text" name="type" value="plan" hidden>
            <span class="text-xl font-semibold text-gray-700 capitalize">à propos du PLAN</span>
            <div class="flex items-center justify-between gap-4">
                <div class="flex flex-col w-full gap-1">
                    <x-input type="text" name="name" label="Nom du plan" required='true' value="{{ $plans->name }}"/>
                    <div id="error_name" class="text-sm text-red-500"></div>
                </div>
                <div class="flex flex-col w-full gap-1">
                    <x-input type="text" name="dedicate" label="Dédier" required='true' value="{{ $plans->dedicate }}"/>
                    <div id="error_dedicate" class="text-sm text-red-500"></div>
                </div>
            </div>
            <div class="flex items-center justify-between gap-4">
                <div class="flex flex-col w-full gap-1">
                    <x-input type="textarea" name="description" label="Description" required='true' value="{{ $plans->description }}"/>
                    <div id="error_description" class="text-sm text-red-500"></div>
                </div>
                <div class="flex flex-col w-full gap-2">
                    <span class="text-gray-600 after:content-['*'] after:ml-0.5 after:text-red-500">Recommander ?</span>
                    <div>
                        <input type="radio" name="is_recommander" value="1">
                        <label for="oui">Oui</label>
                    </div>
                    <div>
                        <input type="radio" name="is_recommander" value="0">
                        <label for="non">Non</label>
                    </div>
                </div>
            </div>
            <span class="text-xl font-semibold text-gray-700 capitalize">Les prix</span>
            <div class="flex items-center justify-between gap-4">
                <div class="flex flex-col w-full gap-1">
                    <x-input type="number" name="price" label="Prix" required='true' value="{{ $plans->price }}"/>
                    <div id="error_price" class="text-sm text-red-500"></div>
                </div>
                <div class="flex flex-col w-full gap-1">
                    <x-input type="number" name="signup_fee" label="Prix d'inscription" required='true' value="{{ $plans->signup_fee }}"/>
                    <div id="error_signup_fee" class="text-sm text-red-500"></div>
                </div>
            </div>
            <span class="text-xl font-semibold text-gray-700 capitalize">Validité</span>
            <div class="flex items-center justify-between gap-4">
                <div class="flex flex-col w-full gap-1">
                    <x-input type="number" name="invoice_period" label="temps de période" required='true' value="{{ $plans->invoice_period }}"/>
                    <div id="error_invoice_period" class="text-sm text-red-500"></div>
                </div>
                <div class="flex flex-col w-full gap-1">
                    <label for="invoice_interval" class="text-gray-600 after:content-['*'] after:ml-0.5 after:text-red-500">Unité de période</label>
                    <select name="invoice_interval" id="invoice_interval" class="p-2 border rounded-lg focus:outline-none focus:ring-purple-500 focus:ring-1">
                        <option value="{{ $plans->invoice_interval }}">{{ $plans->invoice_interval }}</option>
                        <option value="DAY">Jour</option>
                        <option value="MONTH">Mois</option>
                    </select>
                </div>
            </div>
            <span class="text-xl font-semibold text-gray-700 capitalize">Période d'essai</span>
            <div class="flex items-center justify-between gap-4">
                <div class="flex flex-col w-full gap-1">
                    <x-input type="number" name="trial_period" label="Période d'essai" value="{{ $plans->trial_period }}"/>
                    <div id="error_trial_period" class="text-sm text-red-500"></div>
                </div>
                <div class="flex flex-col w-full gap-1">
                    <label for="trial_interval" class="text-gray-600 after:content-['*'] after:ml-0.5 after:text-red-500">Unité de période d'essai</label>
                    <select name="trial_interval" id="trial_interval" class="p-2 border rounded-lg focus:outline-none focus:ring-purple-500 focus:ring-1">
                        <option value="{{ $plans->trial_interval }}">{{ $plans->trial_interval }}</option>
                        <option value="DAY">Jour</option>
                        <option value="MONTH">Mois</option>
                    </select>
                </div>
            </div>
            <span class="text-xl font-semibold text-gray-700 capitalize">Devise</span>
            <div class="flex flex-col w-full gap-1">
                <label for="currency" class="text-gray-600 after:content-['*'] after:ml-0.5 after:text-red-500">Devise</label>
                <select name="currency" id="currency" class="p-2 border rounded-lg focus:outline-none focus:ring-purple-500 focus:ring-1">
                    <option value="{{ $plans->currency }}">{{ $plans->currency }}</option>
                    <option value="Ar">MADAGASCAR - Ariary - AR</option>
                    <option value="GNF">GUINÉE - Franc Guinéen - GNF</option>
                    <option value="ZAR">AFRIQUE DU SUD - Rand - ZAR</option>
                    <option value="XOF">BURKINA FASO - Franc CFA BCEAO - XOF</option>
                    <option value="XAF">CAMEROUNE - Franc CFA BEAC - XAF</option>
                </select>
            </div>
        </div>
        <div class="flex justify-end pt-2">
            <x-btn-ghost>Annuler</x-btn-ghost>
            <x-btn-primary type="submit">Modifier</x-btn-primary>
        </div>
    </form>
</div>

</div>
@endsection

@section('script')
@endsection