@extends('layouts.masterAdmin')

@section('content')
<div class="flex flex-col w-full">
    <div>
        <x-sub-super icon="money-check-dollar" label="Modifier un Feature">
        <x-v-separator />
        </x-sub-super>
    </div>
    <div class="container mt-10">
    <form action="{{ route('crudAbn.update', $features->id) }}" method="POST">
        @csrf
        @method('PATCH')
        <input type="text" name="type" value="feature" hidden>
            <div class="form-group">
                <label for="plan_id">Plan</label>
                <select id="plan_id" name="plan_id" class="form-control">
                    <option value="{{ $features->plan_id }}">{{ $features->plan->name }}</option>
                    @foreach($plans as $plan)
                        <option value="{{ $plan->id }}">{{ $plan->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex flex-col w-full gap-1">
                <x-input type="text" name="name" label="Nom" required='true' value="{{ $features->name }}"/>
                <div id="error_name" class="text-sm text-red-500"></div>
            </div>
            <div class="flex flex-col w-full gap-1">
                <x-input type="text" name="slug" label="Slug" required='true' value="{{ $features->slug }}"/>
                <div id="error_slug" class="text-sm text-red-500"></div>
            </div>
            <div class="flex flex-col w-full gap-1">
                <x-input type="text" name="value" label="Valeur" required='true' value="{{ $features->value }}"/>
                <div id="error_value" class="text-sm text-red-500"></div>
            </div>
            <div class="flex flex-col w-full gap-1">
                <x-input type="number" name="resettable_period" label="Temps de reset" required='true' value="{{ $features->resettable_period }}"/>
                <div id="error_resettable_period" class="text-sm text-red-500"></div>
            </div>
            <div class="flex flex-col w-full gap-1">
                <label for="resettable_interval" class="text-gray-600 after:content-['*'] after:ml-0.5 after:text-red-500">Unité de reset</label>
                    <select name="resettable_interval" id="resettable_interval" class="p-2 border rounded-lg focus:outline-none focus:ring-purple-500 focus:ring-1">
                        <option value="{{ $features->resettable_interval }}">{{ $features->resettable_interval }}</option>
                        <option value="DAY">Jour</option>
                        <option value="MONTH">Mois</option>
                    </select>
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