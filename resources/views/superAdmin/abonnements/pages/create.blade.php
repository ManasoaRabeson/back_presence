@extends('layouts.masterAdmin')

@section('content')
<div class="flex flex-col w-full">
    <div>
        <x-sub-super icon="money-check-dollar" label="Créer un Abonnement">
        <x-v-separator />
        </x-sub-super>
    </div>
    <div class="container w-full mx-auto p-4 mt-10">
    @include('superAdmin.abonnements.pages.tabCreate')
    <form action="{{ route('crudAbn.store') }}" method="POST" class="mt-4">
        @csrf
        <div id="planForm">
            <input type="text" name="type" value="plan" hidden>
            <span class="text-xl font-semibold text-gray-700 capitalize">à propos du PLAN</span>
            <div class="flex items-center justify-between gap-4">
                <div class="flex flex-col w-full gap-1">
                    <x-input type="text" name="name" label="Nom du plan" required='true'/>
                    <div id="error_name" class="text-sm text-red-500"></div>
                </div>
                <div class="flex flex-col w-full gap-1">
                    <label for="user_type" class="text-gray-600 after:content-['*'] after:ml-0.5 after:text-red-500">Pour qui ?</label>
                    <select name="user_type" id="user_type" class="p-2 border rounded-lg focus:outline-none focus:ring-purple-500 focus:ring-1">
                        <option value="centre de formation">Centre de formation</option>
                        <option value="entreprise">Entreprise</option>
                    </select>
                </div>
                <div class="flex flex-col w-full gap-1">
                    <x-input type="text" name="dedicate" label="Dédier" required='true'/>
                    <div id="error_dedicate" class="text-sm text-red-500"></div>
                </div>
            </div>
            <div class="flex items-center justify-between gap-4">
                <div class="flex flex-col w-full gap-1">
                    <x-input type="textarea" name="description" label="Description" required='true'/>
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
                    <x-input type="number" name="price" label="Prix" required='true'/>
                    <div id="error_price" class="text-sm text-red-500"></div>
                </div>
                <div class="flex flex-col w-full gap-1">
                    <x-input type="number" name="signup_fee" label="Prix d'inscription" required='true'/>
                    <div id="error_signup_fee" class="text-sm text-red-500"></div>
                </div>
            </div>
            <span class="text-xl font-semibold text-gray-700 capitalize">Validité</span>
            <div class="flex items-center justify-between gap-4">
                <div class="flex flex-col w-full gap-1">
                    <x-input type="number" name="invoice_period" label="temps de période" required='true' placeholder='1'/>
                    <div id="error_invoice_period" class="text-sm text-red-500"></div>
                </div>
                <div class="flex flex-col w-full gap-1">
                    <label for="invoice_interval" class="text-gray-600 after:content-['*'] after:ml-0.5 after:text-red-500">Unité de période</label>
                    <select name="invoice_interval" id="invoice_interval" class="p-2 border rounded-lg focus:outline-none focus:ring-purple-500 focus:ring-1">
                        <option value="DAY">Jour</option>
                        <option value="MONTH">Mois</option>
                    </select>
                </div>
            </div>
            <span class="text-xl font-semibold text-gray-700 capitalize">Période d'essai</span>
            <div class="flex items-center justify-between gap-4">
                <div class="flex flex-col w-full gap-1">
                    <x-input type="number" name="trial_period" label="Période d'essai" placeholder='1'/>
                    <div id="error_trial_period" class="text-sm text-red-500"></div>
                </div>
                <div class="flex flex-col w-full gap-1">
                    <label for="trial_interval" class="text-gray-600 after:content-['*'] after:ml-0.5 after:text-red-500">Unité de période d'essai</label>
                    <select name="trial_interval" id="trial_interval" class="p-2 border rounded-lg focus:outline-none focus:ring-purple-500 focus:ring-1">
                        <option value="DAY">Jour</option>
                        <option value="MONTH">Mois</option>
                    </select>
                </div>
            </div>
            <span class="text-xl font-semibold text-gray-700 capitalize">Devise</span>
            <div class="flex flex-col w-full gap-1">
                <label for="currency" class="text-gray-600 after:content-['*'] after:ml-0.5 after:text-red-500">Devise</label>
                <select name="currency" id="currency" class="p-2 border rounded-lg focus:outline-none focus:ring-purple-500 focus:ring-1">
                    <option value="Ar">MADAGASCAR - Ariary - AR</option>
                    <option value="GNF">GUINÉE - Franc Guinéen - GNF</option>
                    <option value="ZAR">AFRIQUE DU SUD - Rand - ZAR</option>
                    <option value="XOF">BURKINA FASO - Franc CFA BCEAO - XOF</option>
                    <option value="XAF">CAMEROUNE - Franc CFA BEAC - XAF</option>
                </select>
            </div>
        </div>
        <div id="featureForm"></div>
        <div class="flex justify-end pt-2">
            <x-btn-ghost>Annuler</x-btn-ghost>
            <x-btn-primary type="submit">Ajouter</x-btn-primary>
        </div>
    </form>
</div>
</div>
@endsection

@section('script')
<script>
    function displayPlan() {
        $('#tabPlan').addClass('bg-white');
        $('#tabFeature').removeClass('bg-white');
        $('#featureForm').empty();
        $('#planForm').empty();
        $('#planForm').append(`<div>
            <input type="text" name="type" value="plan" hidden>
            <span class="text-xl font-semibold text-gray-700 capitalize">à propos du PLAN</span>
            <div class="flex items-center justify-between gap-4">
                <div class="flex flex-col w-full gap-1">
                    <x-input type="text" name="name" label="Nom du plan" required='true'/>
                    <div id="error_name" class="text-sm text-red-500"></div>
                </div>
                <div class="flex flex-col w-full gap-1">
                    <label for="user_type" class="text-gray-600 after:content-['*'] after:ml-0.5 after:text-red-500">Pour qui ?</label>
                    <select name="user_type" id="user_type" class="p-2 border rounded-lg focus:outline-none focus:ring-purple-500 focus:ring-1">
                        <option value="centre de formation">Centre de formation</option>
                        <option value="entreprise">Entreprise</option>
                    </select>
                </div>
                <div class="flex flex-col w-full gap-1">
                    <x-input type="text" name="dedicate" label="Dédier" required='true'/>
                <div id="error_dedicate" class="text-sm text-red-500"></div>
            </div>
            </div>
            <div class="flex items-center justify-between gap-4">
                <div class="flex flex-col w-full gap-1">
                    <x-input type="textarea" name="description" label="Description" required='true'/>
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
                    <x-input type="number" name="price" label="Prix" required='true'/>
                    <div id="error_price" class="text-sm text-red-500"></div>
                </div>
                <div class="flex flex-col w-full gap-1">
                    <x-input type="number" name="signup_fee" label="Prix d'inscription" required='true'/>
                    <div id="error_signup_fee" class="text-sm text-red-500"></div>
                </div>
            </div>
            <span class="text-xl font-semibold text-gray-700 capitalize">Validité</span>
            <div class="flex items-center justify-between gap-4">
                <div class="flex flex-col w-full gap-1">
                    <x-input type="number" name="invoice_period" label="temps de période" required='true' placeholder='1'/>
                    <div id="error_invoice_period" class="text-sm text-red-500"></div>
                </div>
                <div class="flex flex-col w-full gap-1">
                    <label for="invoice_interval" class="text-gray-600 after:content-['*'] after:ml-0.5 after:text-red-500">Unité de période</label>
                    <select name="invoice_interval" id="invoice_interval" class="p-2 border rounded-lg focus:outline-none focus:ring-purple-500 focus:ring-1">
                        <option value="DAY">Jour</option>
                        <option value="MONTH">Mois</option>
                    </select>
                </div>
            </div>
            <span class="text-xl font-semibold text-gray-700 capitalize">Période d'essai</span>
            <div class="flex items-center justify-between gap-4">
                <div class="flex flex-col w-full gap-1">
                    <x-input type="number" name="trial_period" label="Période d'essai" placeholder='1'/>
                    <div id="error_trial_period" class="text-sm text-red-500"></div>
                </div>
                <div class="flex flex-col w-full gap-1">
                    <label for="trial_interval" class="text-gray-600 after:content-['*'] after:ml-0.5 after:text-red-500">Unité de période d'essai</label>
                    <select name="trial_interval" id="trial_interval" class="p-2 border rounded-lg focus:outline-none focus:ring-purple-500 focus:ring-1">
                        <option value="DAY">Jour</option>
                        <option value="MONTH">Mois</option>
                    </select>
                </div>
            </div>
            <span class="text-xl font-semibold text-gray-700 capitalize">Devise</span>
            <div class="flex flex-col w-full gap-1">
                <label for="currency" class="text-gray-600 after:content-['*'] after:ml-0.5 after:text-red-500">Devise</label>
                <select name="currency" id="currency" class="p-2 border rounded-lg focus:outline-none focus:ring-purple-500 focus:ring-1">
                    <option value="Ar">MADAGASCAR - Ariary - AR</option>
                    <option value="GNF">GUINÉE - Franc Guinéen - GNF</option>
                    <option value="ZAR">AFRIQUE DU SUD - Rand - ZAR</option>
                    <option value="XOF">BURKINA FASO - Franc CFA BCEAO - XOF</option>
                    <option value="XAF">CAMEROUNE - Franc CFA BEAC - XAF</option>
                </select>
            </div>
        </div>`);
    }

    function displayFeature() {
        $('#tabFeature').addClass('bg-white');
        $('#tabPlan').removeClass('bg-white');
        $('#planForm').empty();
        $('#featureForm').empty();
        $('#featureForm').append(`<div>
            <input type="text" name="type" value="feature" hidden>
            <div class="form-group">
                <label for="plan_id">Plan</label>
                <select id="plan_id" name="plan_id" class="form-control">
                    @foreach($plans as $plan)
                        <option value="{{ $plan->id }}">{{ $plan->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex flex-col w-full gap-1">
                <x-input type="text" name="name" label="Nom" required='true'/>
                <div id="error_name" class="text-sm text-red-500"></div>
            </div>
            <div class="flex flex-col w-full gap-1">
                <x-input type="text" name="slug" label="Slug" required='true'/>
                <div id="error_slug" class="text-sm text-red-500"></div>
            </div>
            <div class="flex flex-col w-full gap-1">
                <x-input type="text" name="value" label="Valeur" required='true'/>
                <div id="error_value" class="text-sm text-red-500"></div>
            </div>
            <div class="flex flex-col w-full gap-1">
                <x-input type="number" name="resettable_period" label="Temps de reset" required='true' placeholder='1'/>
                <div id="error_resettable_period" class="text-sm text-red-500"></div>
            </div>
            <div class="flex flex-col w-full gap-1">
                <label for="resettable_interval" class="text-gray-600 after:content-['*'] after:ml-0.5 after:text-red-500">Unité de reset</label>
                    <select name="resettable_interval" id="resettable_interval" class="p-2 border rounded-lg focus:outline-none focus:ring-purple-500 focus:ring-1">
                        <option value="DAY">Jour</option>
                        <option value="MONTH">Mois</option>
                    </select>
            </div>
        </div>`);
    }
</script>
@endsection